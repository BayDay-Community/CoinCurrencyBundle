<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/6/2018
 * Time: 3:27 PM.
 */

namespace BayDay\CoinCurrencyBundle\Operator;

use BayDay\CoinCurrencyBundle\Model\Customer;
use Doctrine\Common\Collections\Collection;
use SM\Factory\FactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;

/**
 * Class UserWalletOperator.
 */
class UserWalletOperator
{
    /** @var EntityManagerInterface $customerManager */
    private $customerManager;

    /** @var string $coinProductCode */
    private $coinProductCode;

    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    /** @var FactoryInterface */
    private $stateMachineFactory;

    /**
     * UserWalletOperator constructor.
     *
     * @param EntityManagerInterface $customerManager
     * @param FactoryInterface $stateMachineFactory
     * @param string $coinProductCode
     * @param string $coinCurrencyCode
     */
    public function __construct(EntityManagerInterface $customerManager, FactoryInterface $stateMachineFactory, string $coinProductCode, string $coinCurrencyCode)
    {
        $this->customerManager = $customerManager;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->coinProductCode = $coinProductCode;
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    /**
     * @param OrderInterface $order
     *
     * @return Collection
     */
    private function getCoinOrderItems(OrderInterface $order): Collection
    {
        return $order->getItems()->filter(function (OrderItemInterface $orderItem) {
            return $orderItem->getProduct()->getCode() === $this->coinProductCode;
        });
    }

    /**
     * @param OrderInterface $order
     */
    public function payOrder(OrderInterface $order): void
    {
        $coinOrderItems = $this->getCoinOrderItems($order);
        /** @var Customer $customer */
        $customer = $order->getCustomer();
        foreach ($coinOrderItems as $coinOrderItem) {
            $customer->setWallet($customer->getWallet() + intval($coinOrderItem->getVariant()->getCode())*100);
        }
        $this->customerManager->persist($customer);
        $this->customerManager->flush();
    }

    /**
     * @param OrderInterface $order
     */
    public function refundOrder(OrderInterface $order): void
    {
        $coinOrderItems = $this->getCoinOrderItems($order);
        /** @var Customer $customer */
        $customer = $order->getCustomer();
        foreach ($coinOrderItems as $coinOrderItem) {
            $customer->setWallet($customer->getWallet() - intval($coinOrderItem->getVariant()->getCode())*100);
        }
        $this->customerManager->persist($customer);
        $this->customerManager->flush();
    }

    /**
     * @param PaymentInterface $payment
     */
    public function authorizePayment(PaymentInterface $payment): void
    {
        if ($payment->getCurrencyCode() === $this->coinCurrencyCode) {
            $details = $payment->getDetails();

            /** @var Customer $customer */
            $customer = $payment->getOrder()->getCustomer();
            $customer->setWallet($customer->getWallet() - $payment->getAmount());
            $details['status'] = PaymentInterface::STATE_COMPLETED;
            $payment->setDetails($details);

            $stateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);
            $stateMachine->apply(PaymentTransitions::TRANSITION_COMPLETE);
        }
    }

    /**
     * @param PaymentInterface $payment
     */
    public function refundPayment(PaymentInterface $payment): void
    {
        if ($payment->getCurrencyCode() === $this->coinCurrencyCode) {
            $details = $payment->getDetails();

            /** @var Customer $customer */
            $customer = $payment->getOrder()->getCustomer();
            $customer->setWallet($customer->getWallet() + $payment->getAmount());
            $details['status'] = PaymentInterface::STATE_REFUNDED;
            $payment->setDetails($details);

            $stateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);
            $stateMachine->apply(PaymentTransitions::TRANSITION_REFUND);
        }
    }
}
