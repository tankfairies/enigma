<?php

namespace Tests;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Enigma\Alphabet;
use Tankfairies\Enigma\Enigma\AlphabetException;

class AlphabetTest extends Unit
{
    /**
     * @var Alphabet
     */
    protected $alphabet;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->alphabet = new Alphabet();
    }

    protected function _after()
    {
        $this->alphabet = null;
    }

    public function testAlphabetSize()
    {
        $this->assertEquals(26, $this->alphabet->alphabetSize());
    }

    public function testToEnigma()
    {
        $result = $this->alphabet->toEnigma('A');
        $this->assertEquals(0, $result);
    }

    public function testToEnigmaWithInvalidChar()
    {
        $this->tester->expectThrowable(
            new AlphabetException('No corresponding character'),
            function () {
                $this->alphabet->toEnigma('a');
            }
        );
    }

    public function testFromEnigma()
    {
        $result = $this->alphabet->fromEnigma(1);
        $this->assertEquals('B', $result);
    }

    public function testFromEnigmaWithInvalidChar()
    {
        $this->tester->expectThrowable(
            new AlphabetException('No corresponding character'),
            function () {
                $this->alphabet->fromEnigma('a');
            }
        );
    }
}
