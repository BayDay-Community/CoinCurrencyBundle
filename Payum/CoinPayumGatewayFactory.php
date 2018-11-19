<?php

namespace BayDay\CoinCurrencyBundle\Payum;

use BayDay\CoinCurrencyBundle\Payum\Action\CancelAction;
use BayDay\CoinCurrencyBundle\Payum\Action\ConvertPaymentAction;
use BayDay\CoinCurrencyBundle\Payum\Action\RefundAction;
use BayDay\CoinCurrencyBundle\Payum\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class CoinPayumGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'bayday_coin',
            'payum.factory_title' => 'BayDay Coin',
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);
    }
}
