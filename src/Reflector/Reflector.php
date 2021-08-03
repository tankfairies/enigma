<?php
/**
 * Copyright (c) 2021 Tankfairies
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/tankfairies/enigma
 */

namespace Tankfairies\Enigma\Reflector;

use Tankfairies\Enigma\Wiring\Wiring;
use Tankfairies\Enigma\Wiring\WiringInterface;
use Tankfairies\Enigma\Wiring\WiringException;

/**
 * This class represents a Reflector of an Enigma.
 *
 * After its way through plugboard and all rotors, the reflector leads the signal all the way back.
 * Because no letter must connect to itself, its provided that the signal takes a different route.
 * This enables the Enigma to work both for encryption and decryption without any further setup
 *
 * @package Enigma
 */
class Reflector implements ReflectorInterface
{
    /**
     * The wiring of the reflector.
     * Pins are connected in pairs, that means, if 'D' on side A connects to 'H'
     * on side B, 'H' on side A connects to 'D' on side B. No letter must connect to itself!
     *
     * @var Wiring
     */
    private $wiring = null;

    /**
     * List of availabvle reflectors
     *
     * @return array
     */
    public static function availableReflectors(): array
    {
        return [
            self::REFLECTOR_B,
            self::REFLECTOR_C,
            self::REFLECTOR_BTHIN,
            self::REFLECTOR_CTHIN
        ];
    }

    /**
     * Installs the wiring into the reflector
     *
     * @param WiringInterface $wiring
     * @return ReflectorInterface
     */
    public function installWiring(WiringInterface $wiring): ReflectorInterface
    {
        $this->wiring = $wiring;
        return $this;
    }

    /**
     * Send a letter through the wiring.
     * Because pins are connected in pairs, there is no difference if
     * processLetter1stPass() or processLetter2ndPass() is used.
     *
     * @param integer letter to process
     * @return integer resulting letter
     * @throws WiringException
     */
    public function processLetter(int $letter): int
    {
        return $this->wiring->processLetter1stPass($letter);
    }
}
