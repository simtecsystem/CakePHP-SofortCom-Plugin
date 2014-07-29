<?php
App::uses('Component', 'Controller');
class SofortlibComponent extends Component
{
    private $Sofortueberweisung;
    private $Config;
    private $protectedMethods = array('setnotificationurl', 'sendrequest');
    private $states = array('loss', 'pending', 'received', 'refunded', 'untraceable');
    private $newTransactionCallback;
    private $newTransactionCallbackArgs;
    private $shop_id;
    
    /** @var \Controller */
    private $Controller;

    public function __construct($collection)
    {
        parent::__construct($collection);
        $this->Config = Configure::read('SofortComPlugin');
        $this->Sofortueberweisung = new Sofortueberweisung($this->Config['configkey']);
        if (!empty($this->Config['currency']))
            $this->setCurrencyCode($this->Config['currency']);
    }

    public function initialize(\Controller $controller)
    {
        parent::initialize($controller);
        $this->Controller = $controller;
    }

    /**
     * Forward function calls to Sofortueberweisung
     * @param type $name Function name
     * @param type $arguments Function arguments
     * @return type mixed
     */
    public function __call($name, $arguments)
    {
        if(method_exists($this->Sofortueberweisung, $name))
        {
            if (in_array(strtolower($name), $this->protectedMethods))
                throw new InvalidArgumentException("Calling $name is not allowed.");

            return call_user_func_array(array($this->Sofortueberweisung, $name), $arguments);
        }
    }

    /**
     * Set callback function that will be called on a successful payment request
     * response. The first argument for the callback will be the transaction id,
     * the 2nd will be the payment url. You might provide additional arguments
     * for your callback function.
     * @param callable $callable that will be called on successful payment
     * request.
     * @param array $args Optional additional args for the callable.
     */
    public function setNewTransactionCallback($callable, $args = array())
    {
        $this->newTransactionCallback = $callable;
        $this->newTransactionCallbackArgs = $args;
    }

    /**
     * Your shop or order id, or whatever is associated with the generated
     * Sofort.com transaction number. This number will be forwarded to the
     * notifyCallback function.
     *
     * @param int $id unsigned int with your shop or order id
     */
    public function setShopId($id)
    {
        $this->shop_id = $id;
    }

    public function HandleNotifyUrl($eShopId, $status, $ip, $rawPostStream = 'php://input')
    {
        debug($this->Config);
        $shop_id = Security::rijndael(
                self::Base64Decode($eShopId),
                Configure::read('Security.salt'),
                'decrypt');

        $notification = new SofortLibNotification();
        $success = $notification->getNotification(
                file_get_contents($rawPostStream)
        );
        if ($success === false)
            throw new SofortLibNotificationException($notification);

        $transaction = $notification->getTransactionId();
        $time = $notification->getTime();
        App::uses('SofortComNotification', 'SofortCom.Model');
        $SofortComNotification = new SofortComNotification();
        $SofortComNotification->Add($transaction, $status, $time, $ip);

        $transactionData = new SofortLibTransactionData($this->Config['configkey']);
        $transactionData->addTransaction($transaction);
        $transactionData->sendRequest();
        $transactionData->setNumber(1);

        call_user_func_array(
                array($this->Controller, $this->Config['notifyCallback']),
                array($shop_id, $status, $transaction, $time, $transactionData)
        );
    }

    /**
     * Calls Sofortueberweisung::sendRequest and redirects the buyer to
     * the payment url.
     * @throws SofortLibException when Sofortueberweisung returns an error
     * @throws InvalidArgumentException when no shop_id has been set.
     */
    public function PaymentRedirect()
    {
        if (empty($this->shop_id))
            throw new InvalidArgumentException("No shop_id set.");

        $eShopId = rawurlencode(self::Base64Encode(Security::rijndael($this->shop_id, Configure::read('Security.salt'), 'encrypt')));
        $notificationUrl = Router::url('/SofortComPayment/Notify/' . $eShopId, true);
        foreach ($this->states as $state)
            $this->Sofortueberweisung->setNotificationUrl($notificationUrl . '/' . $state, $state);

        App::uses('SofortComShopTransaction', 'SofortCom.Model');
        $SofortComShopTransaction = new SofortComShopTransaction();
        $this->Sofortueberweisung->sendRequest();
        if ($this->Sofortueberweisung->isError())
        {
            $error = $this->Sofortueberweisung->getError();
            $exception = new SofortLibRequestException($error);
            $exception->errors = $this->Sofortueberweisung->getErrors();
            throw $exception;
        }

        $transaction = $this->Sofortueberweisung->getTransactionId();
        $payment_url = $this->Sofortueberweisung->getPaymentUrl();

        $SofortComShopTransaction->Add($transaction, $this->shop_id);

        if (!empty($this->newTransactionCallback) && is_callable($this->newTransactionCallback))
        {
            $args = array($transaction, $payment_url);

            call_user_func_array(
                    $this->newTransactionCallback,
                    array_merge($args, $this->newTransactionCallbackArgs)
            );
        }

        header('Location: ' . $payment_url);
        exit;
    }

    /**
     *
     * @param type $amount
     * @return type amount plus neutralization amount so when Sofort.com subtract it's fee
     * the intended amount will be received.
     * @throws InvalidArgumentException if SofortCom conditions are not set in config
     */
    public static function NeutralizeFee($amount)
    {
        $conditions = self::_getConditionsFromConfig();
        return $amount + ceil(self::CalculateFee($amount) / ( 1 - $conditions['fee_relative'] ));
    }

    /**
     *
     * @param type $amount
     * @return type Sofort.com fee based on amount
     * @throws InvalidArgumentException if SofortCom conditions are not set in config
     */
    public static function CalculateFee($amount)
    {
        $conditions = self::_getConditionsFromConfig();
        return $amount * $conditions['fee_relative'] + $conditions['fee'];
    }

    private static function _getConditionsFromConfig()
    {
        if (empty($this->Config['conditions']))
            throw new InvalidArgumentException('Missing SofortCom conditions.');

        $conditions = $this->Config['conditions'];
        if (!isset($conditions['fee']) || !isset($conditions['fee_relative']))
            throw new InvalidArgumentException('Missing SofortCom condition fees.');

        return $conditions;
    }

    public static function Base64Encode($s)
    {
        return str_replace(array('\\', '/'), array(',', '-'), base64_encode($s));
    }

    public static function Base64Decode($s)
    {
        return base64_decode(str_replace(array(',', '-'), array('\\', '/'), $s));
    }
}

class SofortLibException extends Exception
{
    public $errors;

    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class SofortLibNotificationException extends SofortLibException
{
    public function __construct(SofortLibNotification $sofortLibNotification)
    {
        $message = 'Invalid xml data.';
        if (!empty($sofortLibNotification->errors['error']['message']))
        {
            $message = $sofortLibNotification->errors['error']['message'];
            $this->errors = $sofortLibNotification->errors;
        }
        parent::__construct($message);
    }
}

class SofortLibRequestException extends SofortLibException
{
    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}