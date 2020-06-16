<?php
use Migrations\AbstractMigration;

class AlterSCTransactionOnShopTransactions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('SofortComShopTransactions');
        $table->renameColumn('transaction', 'sc_transaction');
        $table->update();
    }
}