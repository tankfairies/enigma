<?php

namespace Tests\unit\Enigma;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Enigma\AlphabetException;

class AlphabetExceptionTest extends Unit
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
            new AlphabetException('this is a test'),
            function () {
                throw new AlphabetException('this is a test');
            }
        );
    }
}
