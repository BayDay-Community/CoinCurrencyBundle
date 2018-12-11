<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/30/2018
 * Time: 4:14 PM.
 */

namespace BayDay\CoinCurrencyBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BayDayCoinCurrencyBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass( DoctrineOrmMappingsPass::createAnnotationMappingDriver( array('BayDay\CoinCurrencyBundle\Model') , array(realpath(__DIR__.DIRECTORY_SEPARATOR.'Model')) ));
        }

    }
}
