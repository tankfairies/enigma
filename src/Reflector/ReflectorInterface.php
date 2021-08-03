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

use Tankfairies\Enigma\Wiring\WiringInterface;

/**
 * Interface ReflectorInterface
 *
 * @package Enigma
 */
interface ReflectorInterface
{

    /**
     * ID Reflector B
     */
    const REFLECTOR_B = 0;

    /**
     * ID Reflector C
     */
    const REFLECTOR_C = 1;

    /**
     * ID Reflector B Thin
     * only available in model Kriegsmarine M4
     */
    const REFLECTOR_BTHIN = 2;

    /**
     * ID Reflector C Tthin
     * only available in model Kriegsmarine M4
     */
    const REFLECTOR_CTHIN = 3;

    public function installWiring(WiringInterface $wiring): self;
    public function processLetter(int $letter): int;
}
