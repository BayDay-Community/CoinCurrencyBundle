<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/6/2018
 * Time: 3:27 PM
 */

namespace BayDay\CoinCurrencyBundle\Operator;


use BayDay\CoinCurrencyBundle\Entity\ShopUser;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\VarDumper\VarDumper;

class UserWalletOperator
{
    /** @var EntityManagerInterface $entityManager */
    private $shopUserManager;

    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    public function __construct(EntityManagerInterface $shopUserManager, $coinProductCode)
    {
        $this->shopUserManager = $shopUserManager;
        $this->coinProductCode = $coinProductCode;

    }

    private function getTotalCoin($order)
    {
        $totalCoins = 0;

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem)
        {
            if ($orderItem->getProduct()->getCode() !== $this->coinProductCode)
            {
                continue;
            }

            $totalCoins += $orderItem->getQuantity();

        }

        return $totalCoins;
    }


    public function pay(OrderInterface $order)
    {

        $totalCoins = $this->getTotalCoin($order);

        /** @var ShopUser $shopUser */
        $shopUser = $order->getUser();
        if (!$shopUser instanceof ShopUser)
            throw new \RuntimeException("Pls extends ".get_class($order)." with ".ShopUser::class);

        $shopUser->setWallet($shopUser->getWallet() + $totalCoins);
        $this->shopUserManager->persist($shopUser);
        $this->shopUserManager->flush();


    }

    public function refund(OrderInterface $order)
    {

        $totalCoins = $this->getTotalCoin($order);

        /** @var ShopUser $shopUser */
        $shopUser = $order->getUser();
        if (!$shopUser instanceof ShopUser)
            throw new \RuntimeException("Pls extends ".get_class($order)." with ".ShopUser::class);

        $shopUser->setWallet($shopUser->getWallet() - $totalCoins);
        $this->shopUserManager->persist($shopUser);
        $this->shopUserManager->flush();


    }
}