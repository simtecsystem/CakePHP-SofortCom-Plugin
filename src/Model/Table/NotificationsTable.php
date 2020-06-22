<?php

namespace SofortCom\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class NotificationsTable extends Table
{

    public function initialize(array $config): void
    {
        $this->setTable('SofortComNotifications');
        $this->displayField = 'id';
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('sc_transaction')
            ->notEmptyString('sc_transaction');

        $validator
            ->requirePresence('time')
            ->notEmptyString('time');

        $validator
            ->requirePresence('notify_on')
            ->notEmptyString('notify_on');

        return $validator;
    }

    public function Add($transaction, $notifyOn, $time, $ip)
    {
        $record = $this->newEntity(
            [
                'sc_transaction' => $transaction,
                'notify_on' => $notifyOn,
                'time' => $time,
                'ip' => $ip
            ]
        );

        return $this->saveOrFail($record);
    }

}
