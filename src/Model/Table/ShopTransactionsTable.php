<?php

namespace SofortCom\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ShopTransactionsTable extends Table
{
    public function initialize(array $config)
    {
        $this->setTable('SofortComShopTransactions');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('sc_transaction')
            ->notEmptyString('sc_transaction')
            ->numeric('shop_id');

        return $validator;
    }

    /**
     *
     * @param type $transaction
     * @param type $shop_id
     */
    public function Add($transaction, $shop_id)
    {
        $record = $this->newEntity([
            'sc_transaction' => $transaction,
            'shop_id' => $shop_id
        ]);

        return $this->saveOrFail($record);
    }
}
