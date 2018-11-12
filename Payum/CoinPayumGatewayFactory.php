<?php

namespace BayDay\CoinCurrencyBundle\Payum;

use BayDay\CoinCurrencyBundle\Payum\Action\AuthorizeAction;
use BayDay\CoinCurrencyBundle\Payum\Action\CancelAction;
use BayDay\CoinCurrencyBundle\Payum\Action\ConvertPaymentAction;
use BayDay\CoinCurrencyBundle\Payum\Action\CaptureAction;
use BayDay\CoinCurrencyBundle\Payum\Action\NotifyAction;
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
            'payum.action.capture' => new CaptureAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.status' => new StatusAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
