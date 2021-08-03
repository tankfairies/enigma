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
use Tankfairies\Enigma\Wiring\WiringInterface;

/**
 * Interface RotorInterface
 *
 * @package Enigma
 */
interface RotorInterface
{

    /**
     * ID Rotorposition 1
     */
    const ROTOR_1 = 0;

    /**
     * ID Rotorposition 2
     */
    const ROTOR_2 = 1;

    /**
     * ID Rotorposition 3
     */
    const ROTOR_3 = 2;

    /**
     * ID Rotorposition 4
     * only available in model Kriegsmarine M4, also call 'Greek rotor'
     * this rotor never turns
     */
    const ROTOR_GREEK = 3;


    /**
     * ID Rotor I
     */
    const ROTOR_I = 0;

    /**
     * ID Rotor II
     */
    const ROTOR_II = 1;

    /**
     * ID Rotor III
     */
    const ROTOR_III = 2;

    /**
     * ID Rotor IV
     */
    const ROTOR_IV = 3;

    /**
     * ID Rotor V
     */
    const ROTOR_V = 4;

    /**
     * ID Rotor VI
     * only available in model Kriegsmarine M3 and M4
     */
    const ROTOR_VI = 5;

    /**
     * ID Rotor VII
     * only available in model Kriegsmarine M3 and M4
     */
    const ROTOR_VII = 6;

    /**
     * ID Rotor VII
     * only available in model Kriegsmarine M3 and M4
     */
    const ROTOR_VIII = 7;

    /**
     * ID Rotor BETA
     * only available in model Kriegsmarine M4 as 'Greek rotor'
     */
    const ROTOR_BETA = 8;

    /**
     * ID Rotor GAMMA
     * only available in model Kriegsmarine M4 as 'Greek rotor'
     */
    const ROTOR_GAMMA = 9;

    public function installWiring(WiringInterface $wiring, array $notches): self;
    public function setAlphabet(AlphabetInterface $alphabet): self;
    public function advance(): void;
    public function isNotchOpen(): bool;
    public function processLetter1stPass(int $letter): int;
    public function processLetter2ndPass(int $letter): int;
    public function setPosition(int $letter): void;
    public function getPosition(): int;
    public function setRingstellung(int $letter): void;
}
