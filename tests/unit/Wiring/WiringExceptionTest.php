<?php

namespace Tests;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Enigma\AlphabetException;
use Tankfairies\Enigma\Wiring\WiringException;

class WiringExceptionTest extends Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testException()
    {
        $this->tester->expectThrowable(
            new WiringException('this is a test'),
            function () {
                throw new WiringException('this is a test');
            }
        );
    }
}
