<?php

namespace SofortCom\TestCase\Controller\Component;

use Base64Url\Base64Url;

use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventList;
use Cake\TestSuite\TestCase;
use Cake\Utility\Security;

use hakito\Publisher\Published;

use Sofort\SofortLib\Notification;
use Sofort\SofortLib\Sofortueberweisung;
use Sofort\SofortLib\TransactionData;

use SofortCom\Controller\Component\SofortlibComponent;
use SofortCom\Exceptions;

class SofortlibComponentTest extends TestCase {

    /** @var SofortlibComponent */
    private $Component;

    private $originalConfig;

    public $fixtures = ['plugin.SofortCom.Notifications'];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Controller = $this->getMockBuilder('\Cake\Controller\Controller')
            ->setMethods(['redirect'])
            ->getMock();

        $this->registry = new ComponentRegistry($this->Controller);
        $this->Component = new SofortlibComponent($this->registry);
        $this->PComponent = new Published($this->Component);

        $this->Controller->getEventManager()->setEventList(new EventList());
        $this->startUp = new Event('Controller.startup', $this->Controller);
        $this->Component->startup($this->startUp);
    }

    public function testInitialized()
    {
        $this->assertEquals(Configure::read('SofortCom'), $this->PComponent->Config);
        $this->assertInstanceOf(Sofortueberweisung::class, $this->PComponent->Sofortueberweisung);
        $this->assertEquals($this->Controller, $this->PComponent->Controller);
        $this->assertInstanceOf(\SofortCom\Model\Table\NotificationsTable::class, $this->PComponent->Notifications);
        $this->assertInstanceOf(\SofortCom\Model\Table\ShopTransactionsTable::class, $this->PComponent->ShopTransactions);
    }

    public function testCallThrowsArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->Component->setnotificationurl();
    }

    public function testCallForwardsCall()
    {
        $this->PComponent->Sofortueberweisung = $this->getMockBuilder(Sofortueberweisung::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCurrencyCode'])
            ->getMock();

        $this->PComponent->Sofortueberweisung->expects($this->once())
            ->method('setCurrencyCode')
            ->with('ATS');

        $this->Component->setCurrencyCode('ATS');
    }

    public function testShopId()
    {
        $this->Component->setShopId('foo');
        $this->assertEquals('foo', $this->PComponent->shop_id);
    }

    public function testHandleNotifyUrlThrowsNotificationException()
    {
        $eShopId = Base64Url::encode(Security::encrypt('shop', Configure::read('Security.salt')));
        $this->expectException(Exceptions\NotificationException::class);
        $this->Component->HandleNotifyUrl($eShopId, 'pending', '1.2.3.4', 'php://memory');
    }

    public function testHandleNotifyUrl()
    {
        $notification = new Notification();
        $pNotification = new Published($notification);
        $pNotification->_transactionId = 'trans';
        $pNotification->_time = '2020-01-01';

        $component = $this->getMockBuilder(SofortlibComponent::class)
            ->setConstructorArgs([$this->registry])
            ->setMethods(['ParseNotification', 'BuildTransactionData'])
            ->getMock();
        $component->startup($this->startUp);

        $component->expects($this->once())
            ->method('ParseNotification')
            ->willReturn($notification);

        $mTransactionData = $this->getMockBuilder(TransactionData::class)
            ->disableOriginalConstructor()
            ->setMethods(['addTransaction', 'sendRequest', 'setNumber', 'getStatus', 'getStatusReason'])->getMock();
        $mTransactionData->expects($this->once())->method('addTransaction')->with('trans');
        $mTransactionData->expects($this->once())->method('sendRequest');
        $mTransactionData->expects($this->once())->method('setNumber')->with(1);
        $mTransactionData->expects($this->once())->method('getStatus')->willReturn('untraceable');
        $mTransactionData->expects($this->once())->method('getStatusReason')->willReturn('sofort_bank_account_needed');

        $component->expects($this->once())
            ->method('BuildTransactionData')
            ->willReturn($mTransactionData);

        $pComponent = new Published($component, SofortlibComponent::class);

        $pComponent->encryptionKey = 'A dummy key to ensure encyrption key is used';

        $eShopId = Base64Url::encode(Security::encrypt('order_123', $pComponent->encryptionKey));

        \Cake\Event\EventManager::instance()->on('SofortCom.Notify',
        function ($event, $args)
        {
            return ['handled' => true];
        });

        $component->HandleNotifyUrl($eShopId, 'pending', '1.2.3.4', 'php://memory');


        $expectedRecord =
        [
            'id' => 1,
            'sc_transaction' => 'trans',
            'time' => '2020-01-01',
            'notify_on' => 'pending',
            'status' => 'untraceable',
            'status_reason' => 'sofort_bank_account_needed',
            'ip' => '1.2.3.4'

        ];
        $notificationRecord = array_intersect_key($pComponent->Notifications->get(1)->toArray(), $expectedRecord);
        $this->assertEquals($expectedRecord, $notificationRecord);
    }

    public function testHandleNotifyUrlThrowsExceptionIfUnhandled()
    {
        $notification = new Notification();
        $pNotification = new Published($notification);
        $pNotification->_transactionId = 'trans';
        $pNotification->_time = '2020-01-01';

        $component = $this->getMockBuilder(SofortlibComponent::class)
            ->setConstructorArgs([$this->registry])
            ->setMethods(['ParseNotification', 'BuildTransactionData'])
            ->getMock();
        $component->startup($this->startUp);

        $component->expects($this->once())
            ->method('ParseNotification')
            ->willReturn($notification);

        $mTransactionData = $this->getMockBuilder(TransactionData::class)
            ->disableOriginalConstructor()
            ->setMethods(['addTransaction', 'sendRequest', 'setNumber'])->getMock();
        $mTransactionData->expects($this->once())->method('addTransaction')->with('trans');
        $mTransactionData->expects($this->once())->method('sendRequest');
        $mTransactionData->expects($this->once())->method('setNumber')->with(1);

        $component->expects($this->once())
            ->method('BuildTransactionData')
            ->willReturn($mTransactionData);

        $pComponent = new Published($component, SofortlibComponent::class);
        $pComponent->encryptionKey = 'A dummy key to ensure encyrption key is used';

        $eShopId = Base64Url::encode(Security::encrypt('order_123', $pComponent->encryptionKey));

        $exceptionThrown = false;
        try {
            $component->HandleNotifyUrl($eShopId, 'pending', '1.2.3.4', 'php://memory');
        } catch (Exceptions\UnhandledNotificationException $th) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);

        $this->assertEventFiredWith('SofortCom.Notify',
            'args', [
                'shop_id' => 'order_123',
                'notifyOn' => 'pending',
                'transaction' => 'trans',
                'time' => '2020-01-01',
                'data' => $mTransactionData
            ], $this->Controller->getEventManager());
    }

    public function testPaymentRedirectThrowsArgumentException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->Component->PaymentRedirect();
    }

    private function setupLibForPaymentRedirect()
    {
        $this->Component->setShopId('shop');

        $this->PComponent->Sofortueberweisung = $this->getMockBuilder(Sofortueberweisung::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                'setNotificationUrl', 'sendRequest', 'isError', 'getError',
                'getErrors', 'getTransactionId', 'getPaymentUrl'
                ])
            ->getMock();

        $this->PComponent->Sofortueberweisung->expects($this->exactly(4))
            ->method('setNotificationUrl')
            ->with($this->stringStartsWith('http://example.com/SofortComPayment/Notify/'));

        $this->PComponent->Sofortueberweisung->expects($this->once())
            ->method('sendRequest');

        return $this->PComponent->Sofortueberweisung;
    }

    public function testPaymentRedirectThrowsRequestException()
    {
        $sofortueberweisung = $this->setupLibForPaymentRedirect();

        $sofortueberweisung->expects($this->once())
            ->method('isError')->willReturn(true);

        $sofortueberweisung->expects($this->once())
            ->method('getError')->willReturn('fail');

        $sofortueberweisung->expects($this->once())
            ->method('getErrors')->willReturn(['fail1']);

        $e = null;
        try
        {
            $this->Component->PaymentRedirect();
        } catch (Exceptions\RequestException $e)
        {}

        $this->assertNotEmpty($e);
        $this->assertEquals('fail', $e->getMessage());
        $this->assertEquals(['fail1'], $e->errors);
    }

    public function testPaymentRedirectSuccess()
    {
        $sofortueberweisung = $this->setupLibForPaymentRedirect();

        $sofortueberweisung->expects($this->once())
            ->method('getTransactionId')->willReturn('trans');

        $sofortueberweisung->expects($this->once())
            ->method('getPaymentUrl')->willReturn('https://sofort.example.com');

        $pComponent = new Published($this->Component, SofortlibComponent::class);
        $pComponent->ShopTransactions = $this->getMockForModel('SofortCom.ShopTransactions', ['Add']);
        $pComponent->ShopTransactions->expects($this->once())
            ->method('Add')
            ->with('trans', 'shop');

        $this->Controller->expects($this->once())
            ->method('redirect')->with('https://sofort.example.com');

        $this->Component->PaymentRedirect();

        $this->assertEventFiredWith('SofortCom.NewTransaction',
        'args', [
            'transaction' => 'trans',
            'payment_url' => 'https://sofort.example.com'
        ], $this->Controller->getEventManager());
    }

    public function testNeutralizeFee()
    {
        $actual = SofortlibComponent::NeutralizeFee(100);
        $this->assertEquals(127, $actual);
        $this->assertEquals(27, SofortlibComponent::CalculateFee(127));
    }

    public function testCalculateFee()
    {
        $actual = SofortlibComponent::CalculateFee(100);
        $expected = ceil(100 * 0.009 + 25);
        $this->assertEquals($expected, $actual);
    }
}