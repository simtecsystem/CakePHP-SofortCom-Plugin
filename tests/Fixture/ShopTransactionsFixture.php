<?php
namespace SofortCom\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ShopTransactionsFixture extends TestFixture
{

    public $table = 'SofortComShopTransactions';

    public $fields =
    [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'sc_transaction' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 27, 'key' => 'index', 'collate' => 'ascii_general_ci', 'charset' => 'ascii'],
		'shop_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'Shopping cart id, order id or whatever is associated with the transaction number.'],
        '_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['id']],
			'sc_transaction' => ['type' => 'unique', 'columns' => ['sc_transaction', 'shop_id']]
		],
		'_options' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB']
    ];
}