<?php

namespace SofortCom\Test\TestCase\Model\Table;

use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
/**
 * @property \SofortCom\Model\Table\ShopTransactionsTable $ShopTransactions
 */
class ShopTransactionsTest extends TestCase
{
    public $fixtures = ['plugin.SofortCom.ShopTransactions'];

    public function setUp(): void
    {
        parent::setUp();
        $this->ShopTransactions = TableRegistry::getTableLocator()->get('SofortCom.ShopTransactions');

        /** @var EventManager */
        $eventManager = $this->ShopTransactions->getEventManager();
        $eventManager->setEventList(new EventList());
    }

    public function testAdd()
    {
        $added = $this->ShopTransactions->Add('trans', 123);
        $actual = $this->ShopTransactions->findByScTransaction('trans')->first();
        $this->assertEquals('trans', $actual->sc_transaction);
        $this->assertEquals(123, $actual->shop_id);
        $this->assertEquals($actual->shop_id, $added->shop_id);
    }
}