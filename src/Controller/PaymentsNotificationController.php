<?php

namespace SofortCom\Controller;

class PaymentsNotificationController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('SofortCom.Sofortlib', []);
    }

    public function Notify($eShopId, $notifyOn)
    {
        $ip = $this->request->clientIp();
        $this->Sofortlib->HandleNotifyUrl($eShopId, $notifyOn, $ip);
        $this->autoRender = false;
    }
}