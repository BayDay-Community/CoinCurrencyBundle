<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/26/2018
 * Time: 2:46 PM.
 */

namespace BayDay\CoinCurrencyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ShopUser as BaseShopUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ShopUser.
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_shop_user")
 */
class ShopUser extends BaseShopUser
{
    /**
     * @var int
     * @ORM\Column(type="bigint")
     * @Assert\GreaterThanOrEqual(value="0", message="bayday.coin_currency.greater_than_zero.message")
     */
    protected $wallet = 0;

    /**
     * @return int
     */
    public function getWallet()
    {
        return intval($this->wallet);
    }

    /**
     * @param int $wallet
     *
     * @return ShopUser
     */
    public function setWallet(int $wallet): ShopUser
    {
        $this->wallet = $wallet;

        return $this;
    }
}
