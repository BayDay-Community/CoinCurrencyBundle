<?php

declare(strict_types=1);

/**
 * User: donjo
 * Date: 10/5/2018
 * Time: 10:39 AM.
 */

namespace BayDay\CoinCurrencyBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Sylius\Component\Currency\Model\Currency;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Translation\Translator;

/**
 * Class ProductListener.
 */
class CurrencyListener implements EventSubscriber
{
    /** @var Translator $translator */
    private $translator;

    /** @var string $coinCurrenCyCode */
    private $coinCurrencyCode;

    /** {@inheritdoc} */
    public function getSubscribedEvents()
    {
        return [Events::postLoad];
    }

    public function __construct(TranslatorInterface $translator, $coinCurrencyCode)
    {
        $this->translator = $translator;
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    /** {@inheritdoc} */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        if (($currency = $eventArgs->getObject()) instanceof Currency) {
            $currency->setName(Intl::getCurrencyBundle()->getCurrencyName($currency->getCode()));

            if ($currency->getCode() === $this->coinCurrencyCode) {
                $currency->setName($this->translator->trans('bayday.coin_currency.name', [], 'BayDayCoinCurrencyBundle'));
            }
        }
    }
}
