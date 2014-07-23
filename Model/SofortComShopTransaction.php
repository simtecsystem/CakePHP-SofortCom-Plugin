<?php

App::uses('AppModel', 'Model');

/**
 * SofortComShopTransaction Model
 *
 */
class SofortComShopTransaction extends AppModel
{

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'SofortComShopTransactions';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'transaction' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'shop_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    /**
     *
     * @param type $transaction
     * @param type $shop_id
     */
    public function Add($transaction, $shop_id)
    {
        $data = array(
            'transaction' => $transaction,
            'shop_id' => $shop_id,
        );

        $this->create();
        return $this->save($data);
    }

}
