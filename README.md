[![Latest Stable Version](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/v/stable.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin) [![Total Downloads](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/downloads.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin) [![Latest Unstable Version](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/v/unstable.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin) [![License](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/license.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin)

CakePHP-SofortCom-Plugin
========================

[![Build Status](https://travis-ci.org/hakito/CakePHP-SofortCom-Plugin.svg?branch=master)](https://travis-ci.org/hakito/CakePHP-SofortCom-Plugin)
[![Coverage Status](https://coveralls.io/repos/github/hakito/CakePHP-SofortCom-Plugin/badge.svg?branch=master)](https://coveralls.io/github/hakito/CakePHP-SofortCom-Plugin?branch=master)

CakePHP Sofort.com payment plugin

Installation
------------

If you are using composer simply add the following requirement to your composer file:

```json
{
  "require": { "hakito/cakephp-sofortcom-plugin": "dev-master" }
}
```

Otherwise download the plugin to app/Plugin/SofortCom.

Configuration
-------------

Simply load the plugin in your bootstrap:

```php
CakePlugin::load('SofortCom');
```

```php
Configure::write('SofortComPlugin', [
    // Path to autoloader for SDK autoloader
    'sdkLoader' => ROOT . DS . 'Vendor' . DS . 'autoload.php',

    // enter your configuration key
    // you only can create a new configuration key by
    // creating a new Gateway project in your account at sofort.com
    'configkey' => 'dummy:key',

    // This is the name of your function in your AppController
    // that will be called when the notification url gets called
    // Your function will get the arguments:
    // shop_id          Identifier for your order, shopping cart etc.
    // status           status for which this notification was sent
    // transaction      transaction number of notification message
    // time             timestamp of the notification message
    // transationData   SofortLibTransactionData
    'notifyCallback' => 'afterSofortComNotification',

    // Default CurrencyCode.
    // You can override this when preparing the payment request.
    'currency' => 'EUR',

    // The conditions are used if you use the
    // SofortlibComponent::NeutralizeFee function
    'conditions' => [
        'fee' => 25,              // sofort.com fixed fee in cents
        'fee_relative' => '0.009' // relative sofort.com fee
    ]
]);
```

