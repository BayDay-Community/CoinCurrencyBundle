<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/8/2018
 * Time: 4:22 PM.
 */

namespace BayDay\CoinCurrencyBundle\Validator;

use BayDay\CoinCurrencyBundle\Model\Customer;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class WalletFulfilledValidator.
 */
class WalletFulfilledValidator extends ConstraintValidator
{
    /**
     * @param PaymentInterface $payment
     * @param Constraint       $constraint
     */
    public function validate($payment, Constraint $constraint): void
    {
        $customer = $payment->getOrder()->getCustomer();
        if ($customer instanceof Customer &&
            $customer->getWallet() < $payment->getAmount()) {
            $this->context->buildViolation($constraint->message)
                ->setCode(false)
                ->addViolation();
        }
    }
}
