<?php

namespace BayDay\CoinCurrencyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BayDayCoinCurrencyExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('bayday.coin_currency.currency.code', $config['currency_code']);
        $container->setParameter('bayday.coin_currency.product.code', $config['product_code']);
        $container->setParameter('bayday.coin_currency.gateway', $config['gateway']);
        $container->setParameter('bayday.coin_currency.validation_group', $config['validation_group']);
        $container->setParameter('sylius.form.type.checkout_address.validation_groups', array($config['validation_group']));
        $container->setParameter('sylius.form.type.checkout_address.validation_groups', array($config['validation_group']));
        $container->setParameter('sylius.form.type.checkout_select_payment.validation_groups', array($config['validation_group']));
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig('winzou_state_machine');
        $sylius_order_payment = [];
        $sylius_payment = [];
        foreach ($configs as $config) {
            if (isset($config['sylius_order_payment'])) {
                $sylius_order_payment = $config['sylius_order_payment'];
                continue;
            }

            if (isset($config['sylius_payment'])) {
                $sylius_payment = $config['sylius_payment'];
                continue;
            }
        }
        $sylius_order_payment['callbacks']['after']['bayday_manage_currency_paid'] = ['on' => ['pay'],
                                                                                'do' => ['@BayDay\CoinCurrencyBundle\Operator\UserWalletOperator', 'payOrder'],
                                                                                'args' => ['object'],
                                                                                ];
        $sylius_order_payment['callbacks']['after']['bayday_manage_currency_refund'] = ['on' => ['refund'],
                                                                                'do' => ['@BayDay\CoinCurrencyBundle\Operator\UserWalletOperator', 'refundOrder'],
                                                                                'args' => ['object'],
                                                                            ];

        $sylius_payment['callbacks']['after']['bayday_currency_payment_pay'] = ['on' => ['authorize'],
                                                                                'do' => ['@BayDay\CoinCurrencyBundle\Operator\UserWalletOperator', 'authorizePayment'],
                                                                                'args' => ['object'],
                                                                        ];
        $sylius_payment['callbacks']['after']['bayday_currency_payment_refund'] = ['on' => ['refund'],
                                                                                'do' => ['@BayDay\CoinCurrencyBundle\Operator\UserWalletOperator', 'refundPayment'],
                                                                                'args' => ['object'],
                                                                        ];
        $container->prependExtensionConfig('winzou_state_machine', ['sylius_order_payment' => $sylius_order_payment, 'sylius_payment' => $sylius_payment]);
    }
}
