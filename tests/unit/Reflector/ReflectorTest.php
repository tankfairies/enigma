<?php

namespace Tests;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Reflector\Reflector;

class ReflectorTest extends Unit
{
    protected $reflector;
    protected $mockWiring;

    protected function _before()
    {
        $this->reflector = new Reflector();

        $this->mockWiring = $this->make(
            'Tankfairies\Enigma\Wiring\Wiring',
            ['processLetter1stPass' => 9]
        );
    }

    protected function _after()
    {
        $this->reflector = null;
    }

    public function testInstallWiring()
    {
        $this->reflector->installWiring($this->mockWiring);

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Reflector\Reflector', 'wiring');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Wiring\Wiring', $reflection->getValue($this->reflector));
    }

    public function testProcessLetter()
    {
        $this->reflector->installWiring($this->mockWiring);
        $result = $this->reflector->processLetter(3);
        $this->assertEquals(9, $result);
    }
}
