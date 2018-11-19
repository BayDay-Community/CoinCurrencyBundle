<?php

namespace BayDay\CoinCurrencyBundle\Payum\Action;

use BayDay\CoinCurrencyBundle\Entity\ShopUser;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Payum\Core\Bridge\Spl\ArrayObject;
use Sylius\Component\Core\Model\PaymentInterface;

class ConvertPaymentAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var ShopUser $user */
        $user = $payment->getOrder()->getUser();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details->defaults(array(
            'shop_user_id' => $user->getId(),
            'amount' => $payment->getAmount(),
            'status' => PaymentInterface::STATE_NEW,
        ));

        $request->setResult((array) $details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            'array' === $request->getTo()
        ;
    }
}
