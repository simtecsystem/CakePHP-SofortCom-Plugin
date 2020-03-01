<?php

namespace SofortCom\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class NotificationsTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('SofortComNotifications');
        $this->displayField = 'id';
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('sc_transaction')
            ->notEmptyString('sc_transaction');

        $validator
            ->requirePresence('time')
            ->notEmptyString('time');

        $validator
            ->requirePresence('status')
            ->notEmptyString('status');

        return $validator;
    }

    public function Add($transaction, $status, $time, $ip)
    {
        $record = $this->newEntity(
            [
                'sc_transaction' => $transaction,
                'status' => $status,
                'time' => $time,
                'ip' => $ip
            ]
        );

        return $this->saveOrFail($record);
    }

}
