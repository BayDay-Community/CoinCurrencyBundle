<?php

namespace BayDay\CoinCurrencyBundle\Validator;

use Symfony\Component\Validator\Constraints\Currency;

/**
 * @Annotation
 */
class CoinCurrency extends Currency
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'bayday.coin_currency.coin';
}
