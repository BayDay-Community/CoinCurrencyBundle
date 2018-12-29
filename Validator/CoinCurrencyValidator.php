<?php

namespace BayDay\CoinCurrencyBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CurrencyValidator;

/**
 * Class CoinCurrencyValidator.
 */
class CoinCurrencyValidator extends CurrencyValidator
{
    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    /**
     * CoinCurrencyValidator constructor.
     *
     * @param $coinCurrencyCode
     */
    public function __construct(string $coinCurrencyCode)
    {
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    /**
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($this->coinCurrencyCode !== $value) {
            parent::validate($value, $constraint);
        }
    }
}
