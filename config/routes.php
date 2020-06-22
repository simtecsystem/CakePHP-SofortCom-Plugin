<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'SofortCom',
    ['path' => '/SofortComPayment'],
    function (RouteBuilder $routes)
    {
        /* Add route for handling payment notifications */
        $routes->post('/Notify/:eShopId/:notifyOn',
            [
                'controller' => 'PaymentsNotification',
                'action' => 'Notify'
            ]
        )
            ->setPass(['eShopId', 'notifyOn']);
    }
);