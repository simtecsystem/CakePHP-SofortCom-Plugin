<?php

namespace SofortCom\Test\TestCase\Model\Table;

use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * @property \SofortCom\Model\Table\NotificationsTable $Notifications
 */
class NotificationsTest extends TestCase
{
    public $fixtures = ['plugin.SofortCom.Notifications'];

    public function setUp(): void
    {
        parent::setUp();
        $this->Notifications = TableRegistry::getTableLocator()->get('SofortCom.Notifications');

        /** @var EventManager */
        $eventManager = $this->Notifications->getEventManager();
        $eventManager->setEventList(new EventList());
    }

    public function testAdd()
    {
        $added = $this->Notifications->Add('trans', 'pending', 'time', '1.2.3.4');
        $actual = $this->Notifications->findByNotifyOn('pending')->first();
        $this->assertEquals('trans', $actual->sc_transaction);
        $this->assertEquals('pending', $actual->notify_on);
        $this->assertEquals('time', $actual->time);
        $this->assertEquals('1.2.3.4', $actual->ip);
        $this->assertEquals($actual->notify_on, $added->notify_on);
    }
}