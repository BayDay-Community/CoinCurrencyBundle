<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/23/2018
 * Time: 5:45 PM.
 */

namespace BayDay\CoinCurrencyBundle\Converter;

use Sylius\Component\Currency\Converter\CurrencyNameConverterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Translation\Translator;

class CurrencyNameConverter implements CurrencyNameConverterInterface
{
    /** @var CurrencyNameConverterInterface $decoratedConverter */
    private $decoratedConverter;

    /** @var Translator $translator */
    private $translator;

    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    /**
     * CurrencyNameConverter constructor.
     *
     * @param CurrencyNameConverterInterface $decoratedConverter
     * @param TranslatorInterface            $translator
     * @param string                         $coinCurrencyCode
     */
    public function __construct(CurrencyNameConverterInterface $decoratedConverter, TranslatorInterface $translator, $coinCurrencyCode)
    {
        $this->decoratedConverter = $decoratedConverter;
        $this->translator = $translator;
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    public function convertToCode(string $name, ?string $locale = null): string
    {
        try {
            $currencyCode = $this->decoratedConverter->convertToCode($name, $locale);
        } catch (\InvalidArgumentException $e) {
            if ($this->translator->trans('bayday.coin_currency.name', [], 'BayDayCoinCurrencyBundle', $locale) !== $name) {
                throw new \InvalidArgumentException(sprintf('Currency "%s" not found!', $name));
            }

            $currencyCode = $this->coinCurrencyCode;
        }

        return $currencyCode;
    }
}
