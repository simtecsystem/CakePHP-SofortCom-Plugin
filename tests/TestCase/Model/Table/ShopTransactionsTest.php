<?php

namespace SofortCom\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use SofortCom\Model\Table\ShopTransactionsTable;
/**
 * @var \SofortCom\Model\Table\ShopTransactionsTable ShopTransactions
 */
class ShopTransactionsTest extends TestCase
{
    public $fixtures = ['plugin.SofortCom.ShopTransactions'];

    public function setUp()
    {
        $this->ShopTransactions = TableRegistry::getTableLocator()->get('SofortCom.ShopTransactions');
        $this->ShopTransactions->getEventManager()->setEventList(new EventList());
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