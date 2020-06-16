<?php
use Migrations\AbstractMigration;

class AddColumnsOnSofortComNotifications extends AbstractMigration
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
        $table
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => true
            ])
            ->addColumn('status_reason', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => true
            ])
            ->update();
    }
}
