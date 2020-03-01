<?php

namespace SofortCom\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use SofortCom\Model\Table\NotificationsTable;
/**
 * @var \SofortCom\Model\Table\NotificationsTable Notifications
 */
class NotificationsTest extends TestCase
{
    public $fixtures = ['plugin.SofortCom.Notifications'];

    public function setUp()
    {
        $this->Notifications = TableRegistry::getTableLocator()->get('SofortCom.Notifications');
        $this->Notifications->getEventManager()->setEventList(new EventList());
    }

    public function testAdd()
    {
        $added = $this->Notifications->Add('trans', 'state', 'time', '1.2.3.4');
        $actual = $this->Notifications->findByStatus('state')->first();
        $this->assertEquals('trans', $actual->sc_transaction);
        $this->assertEquals('state', $actual->status);
        $this->assertEquals('time', $actual->time);
        $this->assertEquals('1.2.3.4', $actual->ip);
        $this->assertEquals($actual->status, $added->status);
    }
}