<?php
namespace SofortCom\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class NotificationsFixture extends TestFixture
{

    public $table = 'SofortComNotifications';

    public $fields =
    [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'sc_transaction' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 27, 'key' => 'index', 'collate' => 'ascii_general_ci', 'comment' => 'StatusNotification: Transaction number', 'charset' => 'ascii'],
		'time' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 25, 'collate' => 'ascii_general_ci', 'comment' => 'StatusNotification: Date and time (with time zone) according to ISO 8601', 'charset' => 'ascii'],
		'status' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'ascii_general_ci', 'comment' => 'Status type', 'charset' => 'ascii'],
		'ip' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'key' => 'index', 'collate' => 'ascii_general_ci', 'comment' => 'Client IP address', 'charset' => 'ascii'],
        '_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['id']],
			'sc_transaction' => ['type' => 'unique', 'columns' => ['sc_transaction']]
		],
		'_options' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB']
    ];
}