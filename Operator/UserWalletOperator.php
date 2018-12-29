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
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;

/**
 * Class UserWalletOperator.
 */
class UserWalletOperator
{
    /** @var EntityManagerInterface $customerManager */
    private $customerManager;

    /** @var string $coinProductCode */
    private $coinProductCode;

    /**
     * UserWalletOperator constructor.
     *
     * @param EntityManagerInterface $customerManager
     * @param string                 $coinProductCode
     */
    public function __construct(EntityManagerInterface $customerManager, string $coinProductCode)
    {
        $this->customerManager = $customerManager;
        $this->coinProductCode = $coinProductCode;
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
            $customer->setWallet($customer->getWallet() + $coinOrderItem->getQuantity());
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
            $customer->setWallet($customer->getWallet() - $coinOrderItem->getQuantity());
        }
        $this->customerManager->persist($customer);
        $this->customerManager->flush();
    }

    /**
     * @param PaymentInterface $payment
     */
    public function authorizePayment(PaymentInterface $payment): void
    {
        $details = $payment->getDetails();

        /** @var Customer $customer */
        $customer = $payment->getOrder()->getCustomer();
        $customer->setWallet($customer->getWallet() - $payment->getAmount());
        $details['status'] = PaymentInterface::STATE_COMPLETED;
        $payment->setDetails($details);
        $payment->setState(PaymentInterface::STATE_COMPLETED);
    }

    /**
     * @param PaymentInterface $payment
     */
    public function refundPayment(PaymentInterface $payment): void
    {
        $details = $payment->getDetails();
    }
}
