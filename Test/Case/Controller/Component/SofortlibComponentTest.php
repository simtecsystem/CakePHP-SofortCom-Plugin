<?php

App::uses('SofortlibComponent', 'SofortCom.Controller/Component');
App::uses('ComponentCollection', 'Controller');
App::import('SofortCom.Test', 'Config');

class StripePaymentIntentsComponentTest extends CakeTestCase {
    
    /** @var StripePaymentIntentsComponent */
    private $Component;

    private $originalConfig;
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();        
        $Collection = new ComponentCollection();
        $mockedController = $this->getMock('Controller', []);
        $this->Controller = $mockedController;
        $this->Component = new SofortlibComponent($Collection);
        /** @noinspection PhpParamsInspection */
        $this->Component->startup($mockedController);
        Cache::clear();        
    }

    public function testCalculateFee()
    {
        $actual = SofortlibComponent::CalculateFee(100);
        $expected = 100 * 0.009 +  25; 
        $this->assertEquals($expected, $actual);       
    }
}