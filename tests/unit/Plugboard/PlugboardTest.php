<?php

namespace Tests;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Plugboard\Plugboard;

class PlugboardTest extends Unit
{
    protected $plugboard;
    protected $mockWiring;

    protected function _before()
    {
        $this->plugboard = new Plugboard();

        $this->mockWiring = $this->make(
            'Tankfairies\Enigma\Wiring\Wiring',
            [
                'processLetter1stPass' => 9,
                'connect' => null,
                'connectsTo' => 5
            ]
        );
    }

    protected function _after()
    {
        $this->plugboard = null;
    }

    public function testInstallWiring()
    {
        $this->plugboard->installWiring($this->mockWiring);

        $reflection = new \ReflectionProperty('\Tankfairies\Enigma\Plugboard\Plugboard', 'wiring');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Wiring\Wiring', $reflection->getValue($this->plugboard));
    }

    public function testProcessLetter()
    {
        $this->plugboard->installWiring($this->mockWiring);
        $result = $this->plugboard->processLetter(3);
        $this->assertEquals(9, $result);
    }

    public function testPlugLetters()
    {
        $this->plugboard->installWiring($this->mockWiring);
        $this->plugboard->plugLetters(1, 6);
    }

    public function testUnplugLetters()
    {
        $this->plugboard->installWiring($this->mockWiring);
        $this->plugboard->unplugLetters(1);
    }
}
