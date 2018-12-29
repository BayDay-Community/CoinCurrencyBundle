<?php

namespace BayDay\CoinCurrencyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Capture;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\PaymentInterface;
use BayDay\CoinCurrencyBundle\Model\Customer;

/**
 * Class CaptureAction.
 */
class CaptureAction implements ActionInterface
{
    /** @var EntityRepository $shopUserRepository */
    private $customerRepository;

    /**
     * CaptureAction constructor.
     *
     * @param EntityRepository $customerRepository
     */
    public function __construct(EntityRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model['status'] = PaymentInterface::STATE_FAILED;

        /** @var Customer $customer */
        $customer = $this->customerRepository->find($model['customer_id']);
        if ($customer->getWallet() >= (int) $model['amount']) {
            $model['status'] = PaymentInterface::STATE_AUTHORIZED;
        }

        $model->replace((array) $model);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
