<?php

declare(strict_types=1);

/**
 * User: donjo
 * Date: 11/8/2018
 * Time: 4:43 PM.
 */

namespace BayDay\CoinCurrencyBundle\Calculator;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

class CoinCalculator
{
    /** @var string $coinProductCode */
    private $coinProductCode;

    public function __construct($coinProductCode)
    {
        $this->coinProductCode = $coinProductCode;
    }

    /**
     * @param OrderInterface $order
     * @param bool           $cents
     *
     * @return int
     */
    public function getTotalCoinFromOrder(OrderInterface $order, $cents = true): int
    {
        $totalCoins = 0;

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            if ($orderItem->getProduct()->getCode() !== $this->coinProductCode) {
                continue;
            }

            $totalCoins += $orderItem->getQuantity();
        }

        return $cents ? $totalCoins * 100 : $totalCoins;
    }
}
