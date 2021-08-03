<?php

namespace Tests\unit;

use \Codeception\Test\Unit;
use Tankfairies\Enigma\Enigma;
use Tankfairies\Enigma\Enigma\EnigmaException;
use Tankfairies\Enigma\Enigma\EnigmaInterface;
use Tankfairies\Enigma\Plugboard\Plugboard;
use Tankfairies\Enigma\Reflector\Reflector;
use Tankfairies\Enigma\Reflector\ReflectorInterface;
use Tankfairies\Enigma\Rotor\Rotor;
use Tankfairies\Enigma\Rotor\RotorInterface;
use Tankfairies\Enigma\Wiring\Wiring;
use ReflectionProperty;

class EnigmaTest extends Unit
{
    protected $tester;

    protected $enigma;
    protected $mockWiring;
    protected $mockAlphabet;
    protected $mockPlugboard;
    protected $mockRotor;
    protected $mockReflector;

    protected function _before()
    {
        $this->enigma = new Enigma();

        $this->mockWiring = $this->make(
            'Tankfairies\Enigma\Wiring\Wiring',
            ['processLetter1stPass' => 1, 'processLetter2ndPass' => 12]
        );

        $this->mockAlphabet = $this->make(
            'Tankfairies\Enigma\Enigma\Alphabet',
            ['alphabetSize' => 2,
                'toEnigma' => function ($val) {
                    if ($val == 'A') {
                        return 0;
                    }
                    return 1;
                },
                'fromEnigma' => function ($val) {
                    if ($val == 0) {
                        return 'A';
                    }
                    return 'B';
                }]
        );

        $this->mockPlugboard = $this->make(
            'Tankfairies\Enigma\Plugboard\Plugboard',
            ['alphabetSize' => 2]
        );

        $this->mockRotor = $this->make(
            'Tankfairies\Enigma\Rotor\Rotor',
            ['alphabetSize' => 26, 'inUse' => null]
        );

        $this->mockReflector = $this->make(
            'Tankfairies\Enigma\Reflector\Reflector',
            ['alphabetSize' => 26]
        );
    }

    protected function _after()
    {
        $this->enigma = null;
    }

    public function testSetModel()
    {
        $this->enigma->setModel(EnigmaInterface::MODEL_WMLW);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'model');
        $reflection->setAccessible(true);
        $this->assertEquals(EnigmaInterface::MODEL_WMLW, $reflection->getValue($this->enigma));
    }

    public function testSetInvalidModel()
    {
        $this->tester->expectThrowable(
            new EnigmaException('Unknown model'),
            function () {
                $this->enigma->setModel(99);
            }
        );
    }

    public function testInstallRotors()
    {
        $this->enigma->installRotors($this->mockRotor);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'theRotors');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Rotor\Rotor', $reflection->getValue($this->enigma));
    }

    public function testSetRotors()
    {
        $this->enigma->setRotors(
            [RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III]
        );

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'selectedRotors');
        $reflection->setAccessible(true);
        $this->assertEquals(
            [RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III],
            $reflection->getValue($this->enigma)
        );
    }

    public function testSetInvalidRotors()
    {
        $this->tester->expectThrowable(
            new EnigmaException('Unknown rotors'),
            function () {
                $this->enigma->setRotors(
                    [RotorInterface::ROTOR_I, 99, RotorInterface::ROTOR_III]
                );
            }
        );
    }

    public function testInstallReflector()
    {
        $this->enigma->installReflector($this->mockReflector);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'theReflector');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Reflector\Reflector', $reflection->getValue($this->enigma));
    }

    public function testSetReflector()
    {
        $this->enigma->setReflector(ReflectorInterface::REFLECTOR_B);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'selectedReflector');
        $reflection->setAccessible(true);
        $this->assertEquals(ReflectorInterface::REFLECTOR_B, $reflection->getValue($this->enigma));
    }

    public function testSetInvalidReflector()
    {
        $this->tester->expectThrowable(
            new EnigmaException('Unknown reflector'),
            function () {
                $this->enigma->setReflector(99);
            }
        );
    }

    public function testInstalllWiring()
    {
        $this->enigma->installWiring($this->mockWiring);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'wiring');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Wiring\Wiring', $reflection->getValue($this->enigma));
    }

    public function testInstallPlugboard()
    {
        $this->enigma->installPlugboard($this->mockPlugboard);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'plugboard');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Plugboard\Plugboard', $reflection->getValue($this->enigma));
    }

    public function testInstallAplhabet()
    {
        $this->enigma->installAplhabet($this->mockAlphabet);

        $reflection = new ReflectionProperty('\Tankfairies\Enigma\Enigma', 'alphabet');
        $reflection->setAccessible(true);
        $this->assertInstanceOf('\Tankfairies\Enigma\Enigma\Alphabet', $reflection->getValue($this->enigma));
    }

    public function testInitialise()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();
    }

    public function testInitialiseNoModel()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Model not set'),
            function () {
                $this->enigma
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->setReflector(ReflectorInterface::REFLECTOR_B)
                    ->initialise();
            }
        );
    }

    public function testInitialiseNoRotors()
    {
        $this->enigma
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Rotors not installed'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->setReflector(ReflectorInterface::REFLECTOR_B)
                    ->initialise();
            }
        );
    }

    public function testInitialiseRotorsNotSet()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Rotors not set'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setReflector(ReflectorInterface::REFLECTOR_B)->initialise();
            }
        );
    }

    public function testInitialiseNoWiring()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Wiring not installed'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->setReflector(ReflectorInterface::REFLECTOR_B)
                    ->initialise();
            }
        );
    }

    public function testInitialiseNoReflector()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Reflector not installed'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->setReflector(ReflectorInterface::REFLECTOR_B)
                    ->initialise();
            }
        );
    }

    public function testInitialiseReflectorNotSet()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installWiring($this->mockWiring)
            ->installReflector($this->mockReflector)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Reflector not set'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->initialise();
            }
        );
    }

    public function testInitialiseNoPlugboard()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installAplhabet($this->mockAlphabet);

        $this->tester->expectThrowable(
            new EnigmaException('Plugboard not installed'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->setReflector(ReflectorInterface::REFLECTOR_B)
                    ->initialise();
            }
        );
    }

    public function testInitialiseNoAlphabet()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard);

        $this->tester->expectThrowable(
            new EnigmaException('Alphabet not installed'),
            function () {
                $this->enigma
                    ->setModel(EnigmaInterface::MODEL_WMLW)
                    ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
                    ->setReflector(ReflectorInterface::REFLECTOR_B)
                    ->initialise();
            }
        );
    }

    public function testSetRingstellung()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->setRingstellung(RotorInterface::ROTOR_I, 'M');
    }

    public function testPlugLetters()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->plugLetters("A", "G");
    }

    public function testUnplugLetters()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->unplugLetters("A");
    }

    public function testSetPosition()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->setPosition(RotorInterface::ROTOR_I, "D");
    }

    public function testGetPosition()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->getPosition(RotorInterface::ROTOR_I);
    }

    public function testMountRotor()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->mountRotor(0, RotorInterface::ROTOR_I);
    }

    public function testEncodeLetter()
    {
        $this->enigma
            ->installRotors($this->mockRotor)
            ->installReflector($this->mockReflector)
            ->installWiring($this->mockWiring)
            ->installPlugboard($this->mockPlugboard)
            ->installAplhabet($this->mockAlphabet);

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_WMLW)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->mountRotor(0, RotorInterface::ROTOR_III);
    }

    public function testEncode()
    {
        $this->enigma
            ->installRotors(new Rotor())
            ->installReflector(new Reflector())
            ->installWiring(new Wiring())
            ->installPlugboard(new Plugboard())
            ->installAplhabet(new Enigma\Alphabet());

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_KMM3)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->setPosition(RotorInterface::ROTOR_I, "Q");
        $this->enigma->setPosition(RotorInterface::ROTOR_II, "E");
        $this->enigma->setPosition(RotorInterface::ROTOR_III, "V");

        $this->enigma->setRingstellung(RotorInterface::ROTOR_I, "A");
        $this->enigma->setRingstellung(RotorInterface::ROTOR_II, "A");
        $this->enigma->setRingstellung(RotorInterface::ROTOR_III, "A");

        $this->enigma->plugLetters("B", "Q");
        $this->enigma->plugLetters("C", "R");
        $this->enigma->plugLetters("D", "I");
        $this->enigma->plugLetters("E", "J");
        $this->enigma->plugLetters("K", "W");
        $this->enigma->plugLetters("M", "T");
        $this->enigma->plugLetters("O", "S");
        $this->enigma->plugLetters("P", "X");
        $this->enigma->plugLetters("U", "Z");
        $this->enigma->plugLetters("G", "H");

        $message = str_split("HELLOXWORLD");

        $encoded = '';
        foreach ($message as $character) {
            $encoded .= $this->enigma->encodeLetter($character);
        }

        $this->assertEquals("JJFQVQATUCJ", $encoded);
    }

    public function testDecode()
    {
        $this->enigma
            ->installRotors(new Rotor())
            ->installReflector(new Reflector())
            ->installWiring(new Wiring())
            ->installPlugboard(new Plugboard())
            ->installAplhabet(new Enigma\Alphabet());

        $this->enigma
            ->setModel(EnigmaInterface::MODEL_KMM3)
            ->setRotors([RotorInterface::ROTOR_I, RotorInterface::ROTOR_II, RotorInterface::ROTOR_III])
            ->setReflector(ReflectorInterface::REFLECTOR_B)
            ->initialise();

        $this->enigma->setPosition(RotorInterface::ROTOR_I, "Q");
        $this->enigma->setPosition(RotorInterface::ROTOR_II, "E");
        $this->enigma->setPosition(RotorInterface::ROTOR_III, "V");

        $this->enigma->setRingstellung(RotorInterface::ROTOR_I, "A");
        $this->enigma->setRingstellung(RotorInterface::ROTOR_II, "A");
        $this->enigma->setRingstellung(RotorInterface::ROTOR_III, "A");

        $this->enigma->plugLetters("B", "Q");
        $this->enigma->plugLetters("C", "R");
        $this->enigma->plugLetters("D", "I");
        $this->enigma->plugLetters("E", "J");
        $this->enigma->plugLetters("K", "W");
        $this->enigma->plugLetters("M", "T");
        $this->enigma->plugLetters("O", "S");
        $this->enigma->plugLetters("P", "X");
        $this->enigma->plugLetters("U", "Z");
        $this->enigma->plugLetters("G", "H");

        $encoded = str_split("JJFQVQATUCJ");

        $message = '';
        foreach ($encoded as $character) {
            $message .= $this->enigma->encodeLetter($character);
        }

        $this->assertEquals("HELLOXWORLD", $message);
    }
}
