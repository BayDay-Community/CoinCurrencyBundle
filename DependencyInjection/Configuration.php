<?php
/**
 * @author Donjohn
 * @date 09/09/2016
 * @description For ...
 */

namespace BayDay\CoinCurrencyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bayday_coin_currency');

        $rootNode
            ->children()
                ->scalarNode('currency_code')->defaultValue('BDC')->cannotBeEmpty()->end()
                ->scalarNode('product_code')->defaultValue('COIN')->cannotBeEmpty()->end()
                ->scalarNode('gateway')->defaultValue('bayday_coin')->cannotBeEmpty()->end()
                ->scalarNode('validation_group')->defaultValue('BayDayCurrencyCoin')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
