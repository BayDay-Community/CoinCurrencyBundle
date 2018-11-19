<?php

namespace BayDay\CoinCurrencyBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CurrencyValidator;

class CoinCurrencyValidator extends CurrencyValidator
{
    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    public function __construct($coinCurrencyCode)
    {
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($this->coinCurrencyCode !== $value) {
            parent::validate($value, $constraint);
        }
    }
}
