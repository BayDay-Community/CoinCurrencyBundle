<?php

namespace BayDay\CoinCurrencyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Sylius\Component\Core\Model\PaymentInterface;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (PaymentInterface::STATE_NEW === $model['status']) {
            $request->markNew();

            return;
        }

        if (PaymentInterface::STATE_FAILED === $model['status']) {
            $request->markFailed();

            return;
        }

        if (PaymentInterface::STATE_COMPLETED === $model['status']) {
            $request->markCaptured();

            return;
        }

        if (PaymentInterface::STATE_REFUNDED === $model['status']) {
            $request->markRefunded();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
