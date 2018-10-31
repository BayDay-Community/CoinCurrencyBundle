<?php

declare(strict_types=1);
/**
 * User: donjo
 * Date: 10/23/2018
 * Time: 6:09 PM.
 */

namespace BayDay\CoinCurrencyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Currency\Model\Currency as BaseCurrency;

/**
 * Class Currency.
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_currency")
 */
class Currency extends BaseCurrency
{
    /** @var string $name */
    private $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return Currency
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }
}
