<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/6/2018
 * Time: 3:27 PM.
 */

namespace BayDay\CoinCurrencyBundle\Operator;

use BayDay\CoinCurrencyBundle\Entity\ShopUser;
use BayDay\CoinCurrencyBundle\Calculator\CoinCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;

class UserWalletOperator
{
    /** @var EntityManagerInterface $entityManager */
    private $shopUserManager;

    /** @var CoinCalculator $coinCalculator */
    private $coinCalculator;

    public function __construct(EntityManagerInterface $shopUserManager, CoinCalculator $coinCalculator)
    {
        $this->shopUserManager = $shopUserManager;
        $this->coinCalculator = $coinCalculator;
    }

    public function pay(OrderInterface $order)
    {
        $totalCoins = $this->coinCalculator->getTotalCoinFromOrder($order);

        if ($totalCoins) {
            /** @var ShopUser $shopUser */
            $shopUser = $order->getUser();
            if (!$shopUser instanceof ShopUser) {
                throw new \RuntimeException('Pls extends '.\get_class($order).' with '.ShopUser::class);
            }

            $shopUser->setWallet($shopUser->getWallet() + $totalCoins);
            $this->shopUserManager->persist($shopUser);
            $this->shopUserManager->flush();
        }
    }

    public function refund(OrderInterface $order)
    {
        $totalCoins = $this->coinCalculator->getTotalCoinFromOrder($order);

        if ($totalCoins) {
            /** @var ShopUser $shopUser */
            $shopUser = $order->getUser();
            if (!$shopUser instanceof ShopUser) {
                throw new \RuntimeException('Pls extends '.\get_class($order).' with '.ShopUser::class);
            }

            $shopUser->setWallet($shopUser->getWallet() - $totalCoins);
            $this->shopUserManager->persist($shopUser);
            $this->shopUserManager->flush();
        }
    }
}
