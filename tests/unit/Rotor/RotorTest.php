<?php

namespace Tests;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Rotor\Rotor;

class RotorTest extends Unit
{
    protected $rotor;
    protected $mockWiring;
    protected $mockAlphabet;

    protected function _before()
    {
        $this->rotor = new Rotor();

        $this->mockWiring = $this->make(
            'Tankfairies\Enigma\Wiring\Wiring',
            ['processLetter1stPass' => 9, 'processLetter2ndPass' => 12]
        );

        $this->mockAlphabet = $this->make(
            'Tankfairies\Enigma\Enigma\Alphabet',
            ['alphabetSize' => 26]
        );
    }

    protected function _after()
    {
        $this->rotor = new Rotor();
    }

    public function testInstallWiring()
    {
        $this->rotor->installWiring($this->mockWiring, [1, 2, 3]);

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Rotor\Rotor', 'wiring');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Wiring\Wiring', $reflection->getValue($this->rotor));
    }

    public function testSetAlphabet()
    {
        $this->rotor->setAlphabet($this->mockAlphabet);

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Rotor\Rotor', 'alphabetSize');
        $reflection->setAccessible(true);
        $this->assertEquals(26, $reflection->getValue($this->rotor));
    }

    public function testAdvance()
    {
        $this->rotor->setAlphabet($this->mockAlphabet)->advance();

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Rotor\Rotor', 'position');
        $reflection->setAccessible(true);
        $this->assertEquals(1, $reflection->getValue($this->rotor));
    }

    public function testAdvanceToLimit()
    {
        $this->rotor->setAlphabet($this->mockAlphabet);

        for ($i=1; $i<=26; $i++) {
            $this->rotor->advance();
        }

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Rotor\Rotor', 'position');
        $reflection->setAccessible(true);
        $this->assertEquals(0, $reflection->getValue($this->rotor));
    }


    public function testIsNotchOpenTrue()
    {
        $this->rotor
            ->installWiring($this->mockWiring, [1, 2, 3])
            ->setAlphabet($this->mockAlphabet)
            ->advance();

        $this->assertEquals(true, $this->rotor->isNotchOpen());
    }

    public function testIsNotchOpenFalse()
    {
        $this->rotor->installWiring($this->mockWiring, [1, 2, 3]);

        $this->rotor->setAlphabet($this->mockAlphabet);

        for ($i=1; $i<=5; $i++) {
            $this->rotor->advance();
        }

        $this->assertEquals(false, $this->rotor->isNotchOpen());
    }

    public function testProcessLetter1stPass()
    {
        $this->rotor
            ->installWiring($this->mockWiring, [1, 2, 3])
            ->setAlphabet($this->mockAlphabet)
            ->setPosition(2);
        $this->rotor->setRingstellung(1);

        $result = $this->rotor->processLetter1stPass(1);

        $this->assertEquals(8, $result);
    }

    public function testProcessLetter2ndPass()
    {
        $this->rotor
            ->installWiring($this->mockWiring, [1, 2, 3])
            ->setAlphabet($this->mockAlphabet)
            ->setPosition(2);
        $this->rotor->setRingstellung(1);

        $result = $this->rotor->processLetter2ndPass(1);
        $this->assertEquals(11, $result);
    }

    public function testSetPosition()
    {
        $this->rotor->setPosition(5);

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Rotor\Rotor', 'position');
        $reflection->setAccessible(true);
        $this->assertEquals(5, $reflection->getValue($this->rotor));
    }

    public function testGetPosition()
    {
        $this->rotor->setPosition(5);
        $this->assertEquals(5, $this->rotor->getPosition());
    }

    public function testSetRingstellung()
    {
        $this->rotor->setRingstellung(5);

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Rotor\Rotor', 'ringstellung');
        $reflection->setAccessible(true);
        $this->assertEquals(5, $reflection->getValue($this->rotor));
    }
}
