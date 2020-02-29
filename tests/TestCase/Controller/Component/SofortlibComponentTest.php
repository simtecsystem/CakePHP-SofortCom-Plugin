<?php

namespace SofortCom\TestCase\Controller\Component;

use Cake\TestSuite\TestCase;

use SofortCom\Controller\Component\SofortlibComponent;
// use App\Controller\ComponentRegistry;
// App::import('SofortCom.Test', 'Config');

class SofortlibComponentTest extends TestCase {

    /** @var SofortlibComponent */
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
        // $Collection = new ComponentRegistry();
        // $mockedController = $this->getMock('Controller', []);
        // $this->Controller = $mockedController;
        // $this->Component = new SofortlibComponent($Collection);
        // /** @noinspection PhpParamsInspection */
        // $this->Component->startup($mockedController);
        // Cache::clear();
    }

    public function testCalculateFee()
    {
        $actual = SofortlibComponent::CalculateFee(100);
        $expected = 100 * 0.009 +  25;
        $this->assertEquals($expected, $actual);
    }
}