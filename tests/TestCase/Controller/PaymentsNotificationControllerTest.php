<?php
namespace PayPal\Test\TestCase\Controller;

use Cake\TestSuite\TestCase;
use Cake\TestSuite\IntegrationTestTrait;

class PaymentsNotificationControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function setUp(): void
    {
        parent::setup();
        $this->disableErrorHandlerMiddleware();
        $this->component = $this->getMockBuilder(\SofortCom\Controller\Component\SofortlibComponent::class)
            ->disableOriginalConstructor()
            ->setMethods(['HandleNotifyUrl'])
            ->getMock();
        $this->mockComponent = true;
    }

    public function controllerSpy($event, $controller = null)
    {
        /* @var $controller PayPalController */
        $this->controller = $event->getSubject();
        if ($this->mockComponent)
            $this->controller->Sofortlib = $this->component;
    }

    public function testComponentLoaded()
    {
        $this->mockComponent = false;
        try {
            $this->post('/SofortComPayment/Notify/foo/bar');
        } catch (\Throwable $th) {}
        $this->assertInstanceOf(\SofortCom\Controller\Component\SofortlibComponent::class, $this->controller->Sofortlib);
        $this->assertNotEquals($this->component, $this->controller->Sofortlib);
    }

    public function testNotifyCallsComponentHandler()
    {
        $this->configRequest([
            'environment' => ['REMOTE_ADDR' => '1.2.3.4']
        ]);

        $this->component->expects($this->once())
            ->method('HandleNotifyUrl')
            ->with('foo', 'bar', '1.2.3.4');

        $this->post('/SofortComPayment/Notify/foo/bar');
    }
}