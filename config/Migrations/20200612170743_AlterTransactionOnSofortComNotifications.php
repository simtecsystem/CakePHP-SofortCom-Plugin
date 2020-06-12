<?php
use Migrations\AbstractMigration;

class AlterTransactionOnSofortComNotifications extends AbstractMigration
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
        $table = $this->table('SofortComNotifications');
        $table->renameColumn('transaction', 'sc_transaction');
        $table->update();
    }
}
