<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/23/2018
 * Time: 5:40 PM.
 */

namespace BayDay\CoinCurrencyBundle\Templating\Helper;

use Sylius\Bundle\CurrencyBundle\Templating\Helper\CurrencyHelperInterface;

/**
 * Class CurrencyHelper.
 */
class CurrencyHelper implements CurrencyHelperInterface
{
    /** @var CurrencyHelperInterface */
    private $decoratedHelper;

    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    /**
     * CurrencyHelper constructor.
     *
     * @param CurrencyHelperInterface $decoratedHelper
     * @param $coinCurrencyCode
     */
    public function __construct(CurrencyHelperInterface $decoratedHelper, $coinCurrencyCode)
    {
        $this->decoratedHelper = $decoratedHelper;
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    /**
     * @param string $code
     *
     * @return string
     */
    public function convertCurrencyCodeToSymbol(string $code): string
    {
        return $this->decoratedHelper->convertCurrencyCodeToSymbol($code) ?: $this->coinCurrencyCode;
    }
}
