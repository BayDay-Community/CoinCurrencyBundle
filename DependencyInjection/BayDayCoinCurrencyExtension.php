<?php

namespace BayDay\CoinCurrencyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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

        $container->setParameter('bayday.coin_currency.code', $config['code']);
        $container->setParameter('bayday.coin_currency.gateway', $config['gateway']);
        $container->setParameter('bayday.coin_currency.validation_group', $config['validation_group']);
        $container->setParameter('bayday.coin_currency.entity.class', $config['class']);


    }

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('sylius_currency')) {
            return;
        }

        //$gateways = [];
        //$configs = $container->getExtensionConfig('sylius_payment');

//        foreach ($configs as $config) {
//            if (!isset($config['gateways'])) {
//                continue;
//            }
//

//        }
//
//        $container->prependExtensionConfig('sylius_payment', ['gateways' => $gateways]);


        $configs = $container->getExtensionConfig('sylius_currency');


        $resources = [];
        foreach ($configs as $config) {

            if (!isset($config['resources'])) {
                continue;
            }
            foreach ($config['resources'] as $resource => $configResource) {
                $resources[$resource] = $configResource;
            }
        }
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
        $resources['currency'] = ['classes' => ['model' => $config['class']]];

        $container->prependExtensionConfig('sylius_currency', ['resources' => $resources]);

    }

}
