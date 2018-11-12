<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/8/2018
 * Time: 4:21 PM
 */

namespace BayDay\CoinCurrencyBundle\Validator;


use Symfony\Component\Validator\Constraint;

class WalletFulfilled extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


    public $message = 'bayday.coin_currency.wallet_fulfilled';

}