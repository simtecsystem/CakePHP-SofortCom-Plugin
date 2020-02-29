<?php
namespace config\Schema;

class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $SofortComShopTransactions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'transaction' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 27, 'key' => 'index', 'collate' => 'ascii_general_ci', 'charset' => 'ascii'),
		'shop_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'Shopping cart id, order id or whatever is associated with the transaction number.'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'transaction' => array('column' => array('transaction', 'shop_id'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $SofortComNotifications = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'transaction' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 27, 'key' => 'index', 'collate' => 'ascii_general_ci', 'comment' => 'StatusNotification: Transaction number', 'charset' => 'ascii'),
		'time' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 25, 'collate' => 'ascii_general_ci', 'comment' => 'StatusNotification: Date and time (with time zone) according to ISO 8601', 'charset' => 'ascii'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'ascii_general_ci', 'comment' => 'Status type', 'charset' => 'ascii'),
		'ip' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'key' => 'index', 'collate' => 'ascii_general_ci', 'comment' => 'Client IP address', 'charset' => 'ascii'),
        'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'transaction' => array('column' => 'transaction', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
