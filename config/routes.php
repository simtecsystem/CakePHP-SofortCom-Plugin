<?php

/* Add route for handling payment notifications */
Router::connect('/SofortComPayment/Notify/:eShopId/:status', array(
	'plugin' => 'SofortCom',
	'controller' => 'SofortlibPaymentsNotification',
	'action' => 'notify',
        ),
    array(
        'pass' => array('eShopId', 'status')
         )
);