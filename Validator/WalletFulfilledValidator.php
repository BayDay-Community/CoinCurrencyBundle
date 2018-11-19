<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 11/8/2018
 * Time: 4:22 PM.
 */

namespace BayDay\CoinCurrencyBundle\Validator;

use BayDay\CoinCurrencyBundle\Entity\ShopUser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WalletFulfilledValidator extends ConstraintValidator
{
    public function validate($payment, Constraint $constraint)
    {
        $user = $payment->getOrder()->getUser();
        if ($user instanceof ShopUser &&
            $user->getWallet() < $payment->getAmount()) {
            $this->context->buildViolation($constraint->message)
                ->setCode(false)
                ->addViolation();
        }
    }
}
