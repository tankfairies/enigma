<?php
/**
 * Copyright (c) 2021 Tankfairies
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/tankfairies/enigma
 */

namespace Tankfairies\Enigma\Rotor;

use Tankfairies\Enigma\Enigma\AlphabetInterface;
use Tankfairies\Enigma\Wiring\Wiring;
use Tankfairies\Enigma\Wiring\WiringInterface;
use Tankfairies\Enigma\Wiring\WiringException;

/**
 *
 * This class represents a Rotor of an Enigma.
 *
 * The Rotors are the key element of an Enigma. Each provides the monoalphabetical substitution of its wiring,
 * but unlike plugboard and reflector, rotors move, so that the substitution changes.
 *
 * <pre>
 * A B C D E...
 * | | | | | |
 * E K M F L...
 * =>
 * A B C D E...
 * | | | | | |
 * J.E.K.M.F...
 * </pre>
 *
 * Notches mark the positions, where the next rotor may advance
 * The Ringstellung changes the position of the wiring relative to its notches and alphabet.
 *
 * @package Enigma
 */
class Rotor implements RotorInterface
{
    /**
     * The wiring of a rotor.
     *
     * @var Wiring
     */
    private $wiring;

    /**
     * The positions of the notches of a rotor.
     *
     * @var array integer positions of the notches
     */
    private $notches = [];

    /**
     * Actual position of the rotor.
     *
     * @var integer actual rotorpositions
     */
    private $position = 0;

    /**
     * Offset of the wiring.
     *
     * @var integer actual positions rotor
     */
    private $ringstellung = 0;

    /**
     * The size if the aplabet in use.
     *
     * @var int
     */
    private $alphabetSize;

    /**
     * Is the rotor being used
     *
     * @var bool
     */
    private $inUse = false;

    /**
     * @return array
     */
    public static function availableRotors(): array
    {
        return [
            self::ROTOR_I,
            self::ROTOR_II,
            self::ROTOR_III,
            self::ROTOR_IV,
            self::ROTOR_V,
            self::ROTOR_VI,
            self::ROTOR_VII,
            self::ROTOR_VIII,
            self::ROTOR_BETA,
            self::ROTOR_GAMMA
        ];
    }

    /**
     * Installs the wiring into the rotor
     *
     * @param WiringInterface $wiring
     * @param array $notches
     * @return RotorInterface
     */
    public function installWiring(WiringInterface $wiring, array $notches): RotorInterface
    {
        $this->wiring = $wiring;
        $this->notches = $notches;
        return $this;
    }

    /**
     * Sets the enigma alphabet
     *
     * @param AlphabetInterface $alphabet
     * @return RotorInterface
     */
    public function setAlphabet(AlphabetInterface $alphabet): RotorInterface
    {
        $this->alphabetSize = $alphabet->alphabetSize();
        return $this;
    }

    /**
     * Advance the rotor by 1 step.
     * When postion reaches ENIGMA_ALPHABET_SIZE, it is reset to 0.
     *
     * @return void
     */
    public function advance(): void
    {
        $this->position = ($this->position + 1) % $this->alphabetSize;
    }

    /**
     * A notch is open.
     * Returns true if the rotor is in a turnover position for the next rotor
     *
     * @return boolean turnover position reached
     */
    public function isNotchOpen(): bool
    {
        return in_array($this->position, $this->notches);
    }

    /**
     * Send an letter from side A through the wiring to side B.
     * To get the right pin of the wiring, we have to take the current position
     * and the offset given by the ringstellung into account.
     *
     * @param int $letter letter to process
     * @return int resulting letter
     * @throws WiringException
     */
    public function processLetter1stPass(int $letter): int
    {
        $letter = $this->initLetterPass($letter);
        $letter = $this->wiring->processLetter1stPass($letter);

        return $this->finalLetterPass($letter);
    }

    /**
     * Send an letter from side B through the wiring to side A.
     * To get the right pin of the wiring, we have to take the current position
     * and the offset given by the ringstellung into account.
     *
     * @param int $letter letter to process
     * @return int resulting letter
     * @throws WiringException
     */
    public function processLetter2ndPass(int $letter): int
    {
        $letter = $this->initLetterPass($letter);
        $letter = $this->wiring->processLetter2ndPass($letter);

        return $this->finalLetterPass($letter);
    }

    /**
     * Set the rotor to a given position.
     *
     * @param integer position to go to
     * @return void
     */
    public function setPosition(int $letter): void
    {
        $this->position = $letter;
    }

    /**
     * Retrieve current position of the rotor.
     *
     * @return integer current position
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the rotor to in use.
     *
     * @param bool $inUse
     */
    public function setInUse(bool $inUse): void
    {
        $this->inUse = $inUse;
    }

    /**
     * Checks and returns true if the rotor is in use.
     *
     * @return bool
     */
    public function isInUse(): bool
    {
        if (is_null($this->inUse)) {
            return false;
        }
        return $this->inUse;
    }

    /**
     * Sets the ringstellung to a given position.
     *
     * @param integer position to go to
     * @return void
     */
    public function setRingstellung(int $letter): void
    {
        $this->ringstellung = $letter;
    }

    /**
     * @param int $letter
     * @return int
     */
    private function initLetterPass(int $letter): int
    {
        return ($letter - $this->ringstellung + $this->position + $this->alphabetSize) % $this->alphabetSize;
    }

    /**
     * @param int $letter
     * @return int
     */
    private function finalLetterPass(int $letter): int
    {
        return ($letter + $this->ringstellung - $this->position + $this->alphabetSize) % $this->alphabetSize;
    }
}
