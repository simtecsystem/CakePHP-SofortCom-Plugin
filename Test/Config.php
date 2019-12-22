<?php

$settings['SofortComPlugin'] = array(
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
    'conditions' => array(
        'fee' => 25,              // sofort.com fixed fee in cents
        'fee_relative' => '0.009' // relative sofort.com fee
    )
);