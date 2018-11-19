<?php

namespace BayDay\CoinCurrencyBundle\Payum\Action;

use Doctrine\ORM\EntityManagerInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Capture;
use Sylius\Component\Core\Model\PaymentInterface;
use BayDay\CoinCurrencyBundle\Entity\ShopUser;

class CaptureAction implements ActionInterface
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        try {
            /** @var ShopUser $shopUser */
            $shopUser = $this->entityManager->getRepository(ShopUser::class)->find($model['shop_user_id']);
            $shopUser->setWallet($shopUser->getWallet() - $model['amount']);
            $this->entityManager->persist($shopUser);
            $model['status'] = PaymentInterface::STATE_COMPLETED;
        } catch (\Exception $e) {
            $model['status'] = PaymentInterface::STATE_FAILED;
        }

        $model->replace((array) $model);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
