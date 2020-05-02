<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {

        $this->table('SofortComNotifications')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('transaction', 'string', [
                'comment' => 'StatusNotification: Transaction number',
                'default' => null,
                'limit' => 27,
                'null' => false,
            ])
            ->addColumn('time', 'string', [
                'comment' => 'StatusNotification: Date and time (with time zone) according to ISO 8601',
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'comment' => 'Status type',
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('ip', 'string', [
                'comment' => 'Client IP address',
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addIndex(
                [
                    'transaction',
                ]
            )
            ->create();

        $this->table('SofortComShopTransactions')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('transaction', 'string', [
                'default' => null,
                'limit' => 27,
                'null' => false,
            ])
            ->addColumn('shop_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'transaction',
                    'shop_id',
                ]
            )
            ->create();
    }

    public function down()
    {
        $this->table('SofortComNotifications')->drop()->save();
        $this->table('SofortComShopTransactions')->drop()->save();
    }
}
