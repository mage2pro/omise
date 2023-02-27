The module provides a 2-way integration between Magento 2 and the **[Omise](https://www.omise.co/)** payment service.  
Omise works with Thailand, Japan, Indonesia, and Singapore based stores.  
The module is **free** and **open source**.

## Demo videos

1. [**Capture** a card payment](https://mage2.pro/t/2483).
2. [**Capture** a card payment with the **3D Secure** verification](https://mage2.pro/t/2482).
3. [**Authorize** a card payment, and **capture** it from the **Magento** side](https://mage2.pro/t/2481).
4. [**Authorize** a card payment, and **capture** it from the **Omise** side](https://mage2.pro/t/2485).
5. [**Authorize** a card payment, and **reverse** (**void**) it from the **Magento** side](https://mage2.pro/t/2486).
6. [**Capture** a card payment, and **refund** it from the **Magento** side](https://mage2.pro/t/2489).
7. [**Capture** a card payment, and **refund** it from the **Omise** side](https://mage2.pro/t/2491).
8. [**Partial** and **multiple refunds** from the **Magento** side](https://mage2.pro/t/2503).

## How to install
[Hire me in Upwork](https://upwork.com/fl/mage2pro), and I will: 
- install and configure the module properly on your website
- answer your questions
- solve compatiblity problems with third-party checkout, shipping, marketing modules
- implement new features you need 

### 2. Self-installation
```
bin/magento maintenance:enable
rm -f composer.lock
composer clear-cache
composer require mage2pro/omise:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f th_TH en_US <additional locales, e.g.: ja_JP>
bin/magento maintenance:disable
```

## How to update
```
bin/magento maintenance:enable
composer remove mage2pro/omise
rm -f composer.lock
composer clear-cache
composer require mage2pro/omise:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f th_TH en_US <additional locales, e.g.: ja_JP>
bin/magento maintenance:disable
```
