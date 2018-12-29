<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/8/2018
 * Time: 4:21 PM.
 */

namespace BayDay\CoinCurrencyBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class WalletFulfilled.
 */
class WalletFulfilled extends Constraint
{
    /**
     * @return array|string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @var string
     */
    public $message = 'bayday.coin_currency.wallet_fulfilled';
}
