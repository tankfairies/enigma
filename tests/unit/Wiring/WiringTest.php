<?php

namespace Tests\unit\Wiring;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Wiring\Wiring;
use Tankfairies\Enigma\Wiring\WiringException;

class WiringTest extends Unit
{
    protected $wiring;
    protected $mockAlphabet;
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->wiring = new Wiring();
        $this->mockAlphabet = $this->make(
            'Tankfairies\Enigma\Enigma\Alphabet',
            ['toEnigma' => function ($val) {

                switch ($val) {
                    case 'A':
                        return 0;
                        break;
                    case 'C':
                        return 2;
                        break;
                    case 'D':
                        return 3;
                        break;
                    case 'F':
                        return 5;
                        break;
                };
            }]
        );
    }

    protected function _after()
    {
        $this->wiring = new Wiring();
    }

    public function testSetup()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $reflection = new \ReflectionProperty('Tankfairies\Enigma\Wiring\Wiring', 'wiring');
        $reflection->setAccessible(true);
        $this->assertEquals([0 => 0, 1 => 3, 2 => 2, 3 => 5], $reflection->getValue($this->wiring));
    }

    public function testConnect()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $this->wiring->connect(1, 6);

        $reflection = new \ReflectionProperty('Tankfairies\Enigma\Wiring\Wiring', 'wiring');
        $reflection->setAccessible(true);
        $this->assertEquals([0 => 0, 1 => 6, 2 => 2, 3 => 5], $reflection->getValue($this->wiring));
    }

    public function testConnectWithBadPin()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $this->tester->expectThrowable(
            new WiringException('No connection pin'),
            function () {
                $this->wiring->connect(20, 5);
            }
        );
    }

    public function testConnectsTo()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $result = $this->wiring->connectsTo(3);
        $this->assertEquals(5, $result);
    }

    public function testConnectsToWithBadPin()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $this->tester->expectThrowable(
            new WiringException('No connection pin'),
            function () {
                $this->wiring->connectsTo(30);
            }
        );
    }

    public function testProcessLetter1stPass()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $result = $this->wiring->processLetter1stPass(3);
        $this->assertEquals(5, $result);
    }

    public function testProcessLetter1stPassWithBadPin()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $this->tester->expectThrowable(
            new WiringException('No connection pin'),
            function () {
                $this->wiring->processLetter1stPass(36);
            }
        );
    }

    public function testProcessLetter2ndPass()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $result = $this->wiring->processLetter2ndPass(3);
        $this->assertEquals(1, $result);
    }

    public function testProcessLetter2ndPassWithBadPin()
    {
        $this->wiring->setup($this->mockAlphabet, 'ADCF');

        $this->tester->expectThrowable(
            new WiringException('No connection pin'),
            function () {
                $this->wiring->processLetter2ndPass(30);
            }
        );
    }
}
