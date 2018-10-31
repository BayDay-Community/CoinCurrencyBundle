<?php
/**
 * @author Donjohn
 * @date 09/09/2016
 * @description For ...
 */

namespace BayDay\CoinCurrencyBundle\DependencyInjection;


use BayDay\CoinCurrencyBundle\Entity\Currency;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bayday_coin_currency');

        $rootNode
            ->children()
                ->scalarNode('code')->defaultValue('BDC')->cannotBeEmpty()->end()
                ->scalarNode('gateway')->defaultValue('bayday_coin')->cannotBeEmpty()->end()
                ->scalarNode('class')->defaultValue(Currency::class)->cannotBeEmpty()->end()
                ->scalarNode('validation_group')->defaultValue('BayDayCurrencyCoin')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;

    }
}
