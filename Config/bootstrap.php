<?php
/**
 * BEGIN SofortComPlugin Configuration
 * Use these settings to set defaults for the SofortCom component.
 *
 * put this code into your bootstrap.php, so you can override settings.
 */
if (is_null(Configure::read('SofortComPlugin'))) {
	Configure::write('SofortComPlugin', array(
        // Path to autoloader for SDK autoloader
        'sdkLoader' => ROOT . DS . 'Vendor' . DS . 'autoload.php', 
        
        // enter your configuration key
        // you only can create a new configuration key by
        // creating a new Gateway project in your account at sofort.com
        'configkey' => '12345:12345:5dbdad2bc861d907eedfd9528127d002',

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
    ));
}

/** END SofortComPlugin Configuration */


$paypal_settings = Configure::read('SofortComPlugin');
require_once $paypal_settings['sdkLoader'];
unset ($paypal_settings);
