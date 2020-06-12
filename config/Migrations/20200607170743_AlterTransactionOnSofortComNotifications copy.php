<?php
use Migrations\AbstractMigration;

class AlterStatusOnSofortComNotifications extends AbstractMigration
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
        $table->renameColumn('status', 'notify_on');
        $table->update();
    }
}
