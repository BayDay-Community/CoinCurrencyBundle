<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/26/2018
 * Time: 2:46 PM.
 */

namespace BayDay\CoinCurrencyBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Customer as BaseCustomer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Customer.
 *
 * @ORM\MappedSuperclass
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer
{
    /**
     * @var int
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="bayday.coin_currency.greater_than_zero")
     */
    protected $wallet = 0;

    /**
     * @return int
     */
    public function getWallet(): int
    {
        return (int) $this->wallet;
    }

    /**
     * @param int $wallet
     *
     * @return Customer
     */
    public function setWallet(int $wallet): Customer
    {
        $this->wallet = $wallet;

        return $this;
    }
}
