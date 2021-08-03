<?php
/**
 * Copyright (c) 2021 Tankfairies
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/tankfairies/enigma
 */

namespace Tankfairies\Enigma\Wiring;

use Tankfairies\Enigma\Enigma\AlphabetInterface;

/**
 * This class represents the wiring of rotors, reflectors and the plugboard.
 *
 * Each wiring provides a monoalphabetical substitution e.g.:
 *
 * <pre>
 * A B C D E...
 * | | | | | |
 * E K M F L...
 * </pre>
 *
 * @package Enigma
 */
class Wiring implements WiringInterface
{

    /**
     * The connections of the pins.
     *
     * [0]=4 means pin 0 on side A leads to pin 4 on side B, [1]=10 means pin 1
     * on side A leads to pin 10 on side B, ...<br>
     * Size is ENIGMA_ALPHABET_SIZE.
     *
     * @var array integer
     */
    private $wiring;

    /**
     * Connects the pins according to the list in $wiring.
     *
     * example string EKMFLGDQVZNTOWYHXUSPAIBRCJ leads to [0]=4, [1]=10, [2]=12, ...
     *
     * @param AlphabetInterface $alphabet
     * @param string $wiring setup for the internal wiring
     * @return $this|WiringInterface
     */
    public function setup(AlphabetInterface $alphabet, string $wiring): WiringInterface
    {
        $this->wiring = array_map([$alphabet, 'toEnigma'], str_split($wiring));
        return $this;
    }

    /**
     * Manually connect 2 pins.
     *
     * @param int $pin1 pin 1 to connect
     * @param int $pin2 pin 2 to connect
     * @return void
     * @throws WiringException
     */
    public function connect(int $pin1, int $pin2): void
    {
        if (isset($this->wiring[$pin1])) {
            $this->wiring[$pin1] = $pin2;
        } else {
            throw new WiringException('No connection pin');
        }
    }

    /**
     * Get the connected pin.
     *
     * @param integer start of the connection
     * @return integer the connected pin
     * @throws WiringException
     */
    public function connectsTo(int $pin): int
    {
        if (isset($this->wiring[$pin])) {
            return $this->wiring[$pin];
        }

        throw new WiringException('No connection pin');
    }

    /**
     * Pass the given letter form side A to side B by following the connection of the pins.
     *
     * @param integer pin that got activated
     * @return integer pin that gets activated
     * @throws WiringException
     */
    public function processLetter1stPass(int $pin): int
    {
        if (isset($this->wiring[$pin])) {
            return $this->wiring[$pin];
        }

        throw new WiringException('No connection pin');
    }

    /**
     * Pass the given letter form side B to side A by following the connection of the pins.
     *
     * @param integer pin that got activated
     * @return integer pin that gets activated
     * @throws WiringException
     */
    public function processLetter2ndPass(int $pin): int
    {
        $result = array_search($pin, $this->wiring);
        if ($result !== false) {
            return $result;
        }

        throw new WiringException('No connection pin');
    }
}
