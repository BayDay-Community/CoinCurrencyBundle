<?php

namespace BayDay\CoinCurrencyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\VarDumper\VarDumper;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BayDayCoinCurrencyExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('bayday.coin_currency.currency.code', $config['currency_code']);
        $container->setParameter('bayday.coin_currency.product.code', $config['product_code']);
        $container->setParameter('bayday.coin_currency.gateway', $config['gateway']);


    }

    public function prepend(ContainerBuilder $container): void
    {

        $configs = $container->getExtensionConfig('winzou_state_machine');
        foreach ($configs as $config)
        {
            if (!isset($config['sylius_order_payment'])) {
                continue;
            }

            foreach ($config['sylius_order_payment'] as $key => $param) {
                $state_machine[$key] = $param;
            }

        }
        $state_machine['callbacks']['after']["bayday_manage_currency_paid"] = [ "on" => ["pay"],
                                                                                "do" => ["@BayDay\CoinCurrencyBundle\Operator\UserWalletOperator", "pay"],
                                                                                "args" => ["object"]
                                                                                ];
        $state_machine['callbacks']['after']["bayday_manage_currency_refund"] = [ "on" => ["refund"],
                                                                                "do" => ["@BayDay\CoinCurrencyBundle\Operator\UserWalletOperator", "refund"],
                                                                                "args" => ["object"]
                                                                            ];
        $container->prependExtensionConfig('winzou_state_machine', ['sylius_order_payment' => $state_machine]);
    }

}
