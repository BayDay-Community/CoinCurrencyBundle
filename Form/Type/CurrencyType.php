<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/23/2018
 * Time: 6:18 PM.
 */

namespace BayDay\CoinCurrencyBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType as SymfonyCurrencyType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class CurrencyType extends AbstractType
{
    /** @var FormTypeInterface $decoratedForm */
    private $decoratedForm;

    /** @var Translator $translator */
    private $translator;

    /** @var string $coinCurrencyCode */
    private $coinCurrencyCode;

    public function __construct(FormTypeInterface $decoratedForm, TranslatorInterface $translator, $coinCurrencyCode)
    {
        $this->decoratedForm = $decoratedForm;
        $this->translator = $translator;
        $this->coinCurrencyCode = $coinCurrencyCode;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        return $this->decoratedForm->buildView($view, $form, $options);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        return $this->decoratedForm->finishView($view, $form, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $this->decoratedForm->configureOptions($resolver);
    }

    public function getParent()
    {
        return $this->decoratedForm->getParent();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return $this->decoratedForm->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber(SymfonyCurrencyType::class, [
                'label' => 'sylius.form.currency.code',
                'choice_loader' => new IntlCallbackChoiceLoader(function () use ($options) {
                    $locale = $options['choice_translation_locale'] ?? $this->translator->getLocale();
                    $currencies = array_flip(
                        array_merge(
                            [ $this->coinCurrencyCode => $this->translator->trans('bayday.coin_currency.name', [], 'BayDayCoinCurrencyBundle', $locale) ],
                            Intl::getCurrencyBundle()->getCurrencyNames($locale)
                        )
                    );
                    ksort($currencies);

                    return $currencies;
                }),
                'choice_translation_domain' => false,
                'choice_translation_locale' => null,
            ]))
        ;
    }
}
