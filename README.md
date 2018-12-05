Add those lines in your config files (not yet flex compatible).
```yml
sylius_user:
    resources:
        shop:
            user:
                classes:
                    model: BayDay\CoinCurrencyBundle\Entity\ShopUser

sylius_currency:
    resources:
        currency:
            classes:
                model: BayDay\CoinCurrencyBundle\Entity\Currency
                
```
config reference:
```yml
bay_day_coin_currency:
    currency_code: BDC
    product_code: COIN
    gateway: bayday_coin
    validation_group: BayDayCurrencyCoin
```

Create a Gateway in Sylius admin  
Create a Currency called BayDay Coin
Activate the currency in you channels
To sell Coins, create a product with COIN as code (and variants/pricing, check sylius doc). Sell it with normal currency it will be added to the wallet.
To change currency name, override translations
