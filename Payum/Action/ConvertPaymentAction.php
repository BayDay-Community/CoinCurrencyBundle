<?php

namespace BayDay\CoinCurrencyBundle\Payum\Action;

use BayDay\CoinCurrencyBundle\Model\Customer;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Payum\Core\Bridge\Spl\ArrayObject;
use Sylius\Component\Core\Model\PaymentInterface;

/**
 * Class ConvertPaymentAction.
 */
class ConvertPaymentAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var Customer $user */
        $customer = $payment->getOrder()->getCustomer();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details->defaults(array(
            'customer_id' => $customer->getId(),
            'amount' => $payment->getAmount(),
            'status' => PaymentInterface::STATE_NEW,
        ));

        $request->setResult((array) $details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            'array' === $request->getTo()
        ;
    }
}
