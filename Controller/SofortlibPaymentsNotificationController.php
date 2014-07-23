<?php

class SofortlibPaymentsNotificationController extends AppController
{
    public $components = array('SofortCom.Sofortlib');

    public function notify($eShopId, $status)
    {
        $ip = $this->request->clientIp();
        $this->Sofortlib->HandleNotifyUrl($eShopId, $status, $ip);
        $this->autoRender = false;
    }
}