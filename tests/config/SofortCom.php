<?php
return [
    'SofortCom' => [
        // enter your configuration key
        // you only can create a new configuration key by
        // creating a new Gateway project in your account at sofort.com
        'configkey' => 'dummy:api:key',

        // Encryption key for sending encrypted data to SofortCom
        'encryptionKey' => 'A_SECRET_KEY_MUST_BE_32_BYTES_LONG',

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
    ]
];