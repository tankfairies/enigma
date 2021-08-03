<?php
/**
 * Copyright (c) 2021 Tankfairies
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/tankfairies/enigma
 */

namespace Tankfairies\Enigma;

use Tankfairies\Enigma\Enigma\AlphabetInterface;
use Tankfairies\Enigma\Enigma\EnigmaException;
use Tankfairies\Enigma\Enigma\EnigmaInterface;
use Tankfairies\Enigma\Plugboard\Plugboard;
use Tankfairies\Enigma\Plugboard\PlugboardInterface;
use Tankfairies\Enigma\Reflector\Reflector;
use Tankfairies\Enigma\Reflector\ReflectorInterface;
use Tankfairies\Enigma\Rotor\Rotor;
use Tankfairies\Enigma\Rotor\RotorInterface;
use Tankfairies\Enigma\Wiring\WiringInterface;

/**
 * This class represents an Enigma.
 *
 * 3 different models can be emulated with this class, each one has its own set of rotors and
 * reflectors to be used with.
 * Depending on the model, 3 or 4 rotors are mounted, only the first three of them can be triggered by
 * the advance mechanism.
 * A letter is encoded by sending its corresponding signal through the plugboard, rotor 1..3(4), the reflector,
 * rotor 3(4)..1 and the plugboard again.
 * After each encoded letter, the advance mechanism changes the internal setup by rotating the rotors.
 *
 * @package Enigma
 */
class Enigma implements EnigmaInterface
{
    /**
     * The plugboard that connects input and output to the 1st rotor.
     * @var Plugboard
     */
    private $plugboard;

    /**
     * The rotors used by the Enigma.
     * @var array Rotor
     */
    private $rotors;

    /**
     * The reflector used by the Enigma.
     * @var Reflector
     */
    private $reflector;

    /**
     * The rotors available for this model of the Enigma.
     * @var array Rotor
     */
    private $availableRotors;

    /**
     * @var AlphabetInterface
     */
    private $alphabet;

    /**
     * The reflectors available for this model of the Enigma.
     * @var array Reflector
     */
    private $availableReflectors;

    private $rotorConfiguration = [
        RotorInterface::ROTOR_I => [
            'wiring'=> 'EKMFLGDQVZNTOWYHXUSPAIBRCJ',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_Q]
        ],
        RotorInterface::ROTOR_II => [
            'wiring'=> 'AJDKSIRUXBLHWTMCQGZNPYFVOE',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_E]
        ],
        RotorInterface::ROTOR_III => [
            'wiring'=> 'BDFHJLCPRTXVZNYEIWGAKMUSQO',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_V]
        ],
        RotorInterface::ROTOR_IV => [
            'wiring'=> 'ESOVPZJAYQUIRHXLNFTGKDCMWB',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_J]
        ],
        RotorInterface::ROTOR_V => [
            'wiring'=> 'VZBRGITYUPSDNHLXAWMJQOFECK',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_Z]
        ],
        RotorInterface::ROTOR_VI => [
            'wiring'=> 'JPGVOUMFYQBENHZRDKASXLICTW',
            'used'=> [self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_M, AlphabetInterface::ENIGMA_KEY_Z]
        ],
        RotorInterface::ROTOR_VII => [
            'wiring'=> 'NZJHGRCXMYSWBOUFAIVLPEKQDT',
            'used'=> [self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_M, AlphabetInterface::ENIGMA_KEY_Z]
        ],
        RotorInterface::ROTOR_VIII => [
            'wiring'=> 'FKQHTLXOCBJSPDZRAMEWNIUYGV',
            'used'=> [self::MODEL_KMM3, self::MODEL_KMM4],
            'notches'=> [AlphabetInterface::ENIGMA_KEY_M, AlphabetInterface::ENIGMA_KEY_Z]
        ],
        RotorInterface::ROTOR_BETA => [
            'wiring'=> 'LEYJVCNIXWPBQMDRTAKZGFUHOS',
            'used'=> [self::MODEL_KMM4],
            'notches'=> []
        ],
        RotorInterface::ROTOR_GAMMA => [
            'wiring'=> 'FSOKANUERHMBTIYCWLQPZXVGJD',
            'used'=> [self::MODEL_KMM4],
            'notches'=> []
        ]
    ];

    private $reflectoConfiguration = [
        ReflectorInterface::REFLECTOR_B => [
            'wiring'=> 'YRUHQSLDPXNGOKMIEBFZCWVJAT',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3]
        ],
        ReflectorInterface::REFLECTOR_C => [
            'wiring'=> 'FVPJIAOYEDRZXWGCTKUQSBNMHL',
            'used'=> [self::MODEL_WMLW, self::MODEL_KMM3]
        ],
        ReflectorInterface::REFLECTOR_BTHIN => [
            'wiring'=> 'ENKQAUYWJICOPBLMDXZVFTHRGS',
            'used'=> [self::MODEL_KMM4]
        ],
        ReflectorInterface::REFLECTOR_CTHIN => [
            'wiring'=> 'RDOBJNTKVEHMLFCWZAXGYIPSUQ',
            'used'=> [self::MODEL_KMM4]
        ]
    ];

    private $theRotors;
    private $selectedRotors;
    private $theReflector;
    private $selectedReflector;
    private $wiring;
    private $model;

    /**
     * Sets up the plugboard and creates the rotors and reflectors available for the given model.
     * The initital rotors and reflectros are mounted.
     *
     * @throws EnigmaException
     */
    public function initialise()
    {
        if (!($this->theRotors instanceof RotorInterface)) {
            throw new EnigmaException('Rotors not installed');
        }
        if (!($this->theReflector instanceof ReflectorInterface)) {
            throw new EnigmaException('Reflector not installed');
        }
        if (!($this->wiring instanceof WiringInterface)) {
            throw new EnigmaException('Wiring not installed');
        }
        if (!($this->plugboard instanceof PlugboardInterface)) {
            throw new EnigmaException('Plugboard not installed');
        }
        if (!($this->alphabet instanceof AlphabetInterface)) {
            throw new EnigmaException('Alphabet not installed');
        }

        if (is_null($this->model)) {
            throw new EnigmaException('Model not set');
        }
        if (empty($this->selectedRotors)) {
            throw new EnigmaException('Rotors not set');
        }
        if (is_null($this->selectedReflector)) {
            throw new EnigmaException('Reflector not set');
        }

        //plugboard
        $wiring = '';
        for ($idx=0; $idx<$this->alphabet->alphabetSize(); $idx++) {
            $wiring .= $this->alphabet->fromEnigma($idx);
        }
        $this->plugboard->installWiring((clone $this->wiring)->setup($this->alphabet, $wiring));

        //rotors
        foreach ($this->rotorConfiguration as $key => $r) {
            if (in_array($this->model, $r["used"])) {
                $this->availableRotors[$key]
                    = (clone $this->theRotors)
                    ->installWiring(
                        (clone $this->wiring)->setup($this->alphabet, $r["wiring"]),
                        $r["notches"]
                    )
                    ->setAlphabet($this->alphabet);
            }
        }
        foreach ($this->selectedRotors as $position => $rotor) {
            $this->mountRotor($position, $rotor);
        }

        //reflector
        foreach ($this->reflectoConfiguration as $key => $r) {
            if (in_array($this->model, $r["used"])) {
                $this->availableReflectors[$key]
                    = (clone $this->theReflector)
                    ->installWiring((clone $this->wiring)->setup($this->alphabet, $r["wiring"]));
            }
        }
        $this->mountReflector($this->selectedReflector);
    }

    /**
     * Sets the enigma model
     *
     * @param int $model
     * @return $this
     * @throws EnigmaException
     */
    public function setModel(int $model): self
    {
        if (!in_array($model, [self::MODEL_KMM3, self::MODEL_KMM4, self::MODEL_WMLW])) {
            throw new EnigmaException('Unknown model');
        }

        $this->model = $model;
        return $this;
    }

    /**
     * Installs the rotor
     *
     * @param RotorInterface $theRotors
     * @return $this
     */
    public function installRotors(RotorInterface $theRotors): self
    {
        $this->theRotors = $theRotors;
        return $this;
    }

    /**
     * Installs the reflector
     *
     * @param ReflectorInterface $theReflector
     * @return $this
     */
    public function installReflector(ReflectorInterface $theReflector): self
    {
        $this->theReflector = $theReflector;
        return $this;
    }

    /**
     * Installs the reflector
     *
     * @param WiringInterface $wiring
     * @return $this
     */
    public function installWiring(WiringInterface $wiring): self
    {
        $this->wiring = $wiring;
        return $this;
    }

    /**
     * Installs the plugboard
     *
     * @param PlugboardInterface $thePlugboard
     * @return $this
     */
    public function installPlugboard(PlugboardInterface $thePlugboard): self
    {
        $this->plugboard = $thePlugboard;
        return $this;
    }

    /**
     * Sets the alphabet
     *
     * @param AlphabetInterface $alphabet
     * @return $this
     */
    public function installAplhabet(AlphabetInterface $alphabet): self
    {
        $this->alphabet = $alphabet;
        return $this;
    }

    /**
     * sets which reflector configuration
     *
     * @param int $selectedReflector
     * @return $this
     * @throws EnigmaException
     */
    public function setReflector(int $selectedReflector): self
    {
        if (!in_array($selectedReflector, Reflector::availableReflectors())) {
            throw new EnigmaException('Unknown reflector');
        }
        $this->selectedReflector = $selectedReflector;
        return $this;
    }

    /**
     * Sets the rotors and order
     *
     * @param array $selectedRotors
     * @return $this
     * @throws EnigmaException
     */
    public function setRotors(array $selectedRotors): self
    {
        $unknownRotor = false;
        foreach ($selectedRotors as $rotor) {
            if (!in_array($rotor, Rotor::availableRotors())) {
                $unknownRotor = true;
                break;
            }
        }

        if ($unknownRotor) {
            throw new EnigmaException('Unknown rotors');
        }

        $this->selectedRotors = $selectedRotors;
        return $this;
    }

    /**
     * Mount a rotor into the enigma.
     * A rotor may only be used in one position at a time, so if an rotor is already in use nothing is changed.
     * The previously used rotor will be replaced.
     *
     * @param integer ID of the position to set the rotor
     * @param integer ID of the rotor to use
     * @return void
     */
    public function mountRotor(int $position, int $rotor): void
    {
        if ($this->availableRotors[$rotor]->isInUse()) {
            return;
        }
        if (isset($this->rotors[$position])) {
            $this->rotors[$position]->setInUse(false);
        }
        $this->rotors[$position] = $this->availableRotors[$rotor];
        $this->rotors[$position]->setInUse(true);
    }

    /**
     * Mount a reflector into the enigma.
     * The previously used reflector will be replaced.
     *
     * @param integer ID of the reflector to use
     * @return void
     */
    public function mountReflector(int $reflector): void
    {
        $this->reflector = $this->availableReflectors[$reflector];
    }

    /**
     * Encode a single letter.
     * The letter passes the plugboard, the rotors, the reflector, the rotors in
     * the opposite direction and again the plugboard.
     * Every encoding triggers the advancemechanism.
     *
     * @param string $letter letter to encode
     * @return string encoded letter
     * @throws Wiring\WiringException
     */
    public function encodeLetter(string $letter): string
    {
        $this->advance();
        $letter = $this->alphabet->toEnigma($letter);
        $letter = $this->plugboard->processLetter($letter);

        for ($idx=0; $idx<count($this->rotors); $idx++) {
            $letter = $this->rotors[$idx]->processLetter1stPass($letter);
        }

        $letter = $this->reflector->processLetter($letter);

        for ($idx=(count($this->rotors)-1); $idx>-1; $idx--) {
            $letter = $this->rotors[$idx]->processLetter2ndPass($letter);
        }

        $letter = $this->plugboard->processLetter($letter);
        return $this->alphabet->fromEnigma($letter);
    }

    /**
     * Turn a rotor to a new position.
     *
     * @param integer ID of the rotor to turn
     * @param string letter to turn to
     * @return void
     */
    public function setPosition(int $position, string $letter): void
    {
        $this->rotors[$position]->setPosition($this->alphabet->toEnigma($letter));
    }

    /**
     * Get the current position of a rotor.
     *
     * @param integer ID of the rotor
     * @return string current position
     */
    public function getPosition(int $position): string
    {
        return $this->alphabet->toEnigma($this->rotors[$position]->getPosition());
    }

    /**
     * Turn the ringstellung of a rotor to a new position.
     *
     * @param integer ID of the rotor
     * @param string letter to turn to
     * @return void
     */
    public function setRingstellung(int $position, string $letter): void
    {
        $this->rotors[$position]->setRingstellung($this->alphabet->toEnigma($letter));
    }

    /**
     * Connect 2 letters on the plugboard.
     * The letter are transformed to integer first
     *
     * @param string $letter1 letter 1 to connect
     * @param string $letter2 letter 2 to connect
     * @throws Wiring\WiringException
     */
    public function plugLetters(string $letter1, string $letter2): void
    {
        $this->plugboard->plugLetters($this->alphabet->toEnigma($letter1), $this->alphabet->toEnigma($letter2));
    }

    /**
     * Disconnects 2 letters on the plugboard.
     * Because letters are connected in pairs, we only need to know one of them.
     *
     * @param string $letter 1 of the 2 letters to disconnect
     * @throws Wiring\WiringException
     */
    public function unplugLetters(string $letter): void
    {
        $this->plugboard->unplugLetters($this->alphabet->toEnigma($letter));
    }

    /**
     * Advance the rotors.
     * Rotor 1 advances every time, rotor 2 when a notch on rotor 1 is open
     * and when rotor 3 advances, rotor 3 when a notch on rotor 2 is open
     *
     * @return void
     */
    private function advance(): void
    {
        if ($this->rotors[1]->isNotchOpen()) {
            $this->rotors[2]->advance();
            $this->rotors[1]->advance();
        }
        if ($this->rotors[0]->isNotchOpen()) {
            $this->rotors[1]->advance();
        }
        $this->rotors[0]->advance();
    }
}
