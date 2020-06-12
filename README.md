[![Latest Stable Version](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/v/stable.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin) [![Total Downloads](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/downloads.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin) [![Latest Unstable Version](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/v/unstable.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin) [![License](https://poser.pugx.org/hakito/cakephp-sofortcom-plugin/license.svg)](https://packagist.org/packages/hakito/cakephp-sofortcom-plugin)

# CakePHP-SofortCom-Plugin

[![Build Status](https://travis-ci.org/hakito/CakePHP-SofortCom-Plugin.svg?branch=master)](https://travis-ci.org/hakito/CakePHP-SofortCom-Plugin)
[![Coverage Status](https://coveralls.io/repos/github/hakito/CakePHP-SofortCom-Plugin/badge.svg?branch=master)](https://coveralls.io/github/hakito/CakePHP-SofortCom-Plugin?branch=master)

CakePHP Sofort.com payment plugin

# Installation

If you are using composer simply add the plugin using the command

```bash
composer require hakito/sofortcom-plugin
```

Otherwise download the plugin to app/Plugin/SofortCom. Add a PSR-4 compatible autoloader to your bootstrap.

Load the plugin in your bootstrap:

```php
public function bootstrap()
{
    // Call parent to load bootstrap from files.
    parent::bootstrap();

    $this->addPlugin(\SofortCom\Plugin::class, ['routes' => true]);
}
```

# Creating tables

Create the database tables with the following command:

```bash
bin/cake migrations migrate -p SofortCom
```

# Configuration

In your app.local.php add an entry for SofortCom

```php
[
  'SofortCom' => [
    // enter your configuration key
    // you only can create a new configuration key by
    // creating a new Gateway project in your account at sofort.com
    'configkey' => 'dummy:key',

    // Encryption key for sending encrypted data to SofortCom
    'encryptionKey' => 'A_SECRET_KEY_MUST_BE_32_BYTES_LONG',

    // Default CurrencyCode.
    // You can override this when preparing the payment request.
    'currency' => 'EUR',

    // The conditions are used if you use the
    // SofortlibComponent::NeutralizeFee function
    'conditions' => [
        'fee' => 25,              // sofort.com fixed fee in cents
        'fee_relative' => '0.009' // relative sofort.com fee
    ]
  ]
];
```

# Usage

In your payment handling controller:

```php
    // Load the component
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('SofortCom.Sofortlib');
    }

    // Sample checkout
    private function _checkoutSofortCom($orderId)
    {
        $this->Sofortlib->setAmount(12.34);
        $this->Sofortlib->setCurrencyCode('EUR');
        $this->Sofortlib->setReason('Buying Great Stuff', 'Order ' . $orderId); // Displayed as payment reason to the user
        $this->Sofortlib->setSuccessUrl('http://your-shop.example.com/success'); // The URL your clients are redirected upon success
        $this->Sofortlib->setAbortUrl('http://your-shop.example.com/abort'); // The URL your clients are redirected upon abort
        $this->Sofortlib->setShopId($orderId);

        try
        {
            $this->Sofortlib->PaymentRedirect();
        } catch (SofortLibException $ex) {
            Log::error(sprintf('SofortCom payment redirect errors: "%s"', var_export($ex->errors, true)));
            $this->Flash->set(__('Could not redirect to online banking (Error {0}). Please choose another payment method.', $ex->getMessage()));
            return $this->redirect('http://your-shop.example.com/recover');
        }
    }
```

## Event handlers

You must implement at least the following eventhandler:

### SofortCom.Notify

```php
\Cake\Event\EventManager::instance()->on('SofortCom.Notify',
function ($event, $args)
{
  // $args =
  // [
  //   'shop_id' => 'order123',                   // Some id defined by you upon payment initialization
  //   'notifyOn' => 'pending',                   // SofortCom notification URL suffix
  //   'transaction' => '99999-53245-5483-4891',  // SofortCom transaction id
  //   'time' => '2010-04-14T19:01:08+02:00',     // SofortCom timestamp of notification
  //   'data' => {object},                        // Instance of \SofortLibTransactionData
  // ]


  return ['handled' => true]; // If you don't set the handled flag to true
                              // the plugin will throw an UnhandledNotificationException
});
```

### SofortCom.NewTransaction

This event is optional and fired before the user is redirected to the payment URL.
It provides the following arguments:

```php
$args =
[
  'transaction' => '99999-53245-5483-4891',    // SofortCom transaction id
  'payment_url' => 'http://sofort.com/example' // SofortCom payment redirect url
]
```
