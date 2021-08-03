<?php
/**
 * Copyright (c) 2021 Tankfairies
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/tankfairies/enigma
 */

namespace Tankfairies\Enigma\Enigma;

/**
 * Interface EnigmaInterface
 *
 * @package Enigma
 */
interface EnigmaInterface
{

    /**
     * Wehrmacht/Luftwaffe (3-rotor model)
     */
    const MODEL_WMLW = 0;

    /**
     * Kriegsmarine M3 (3-rotor model)
     */
    const MODEL_KMM3 = 1;

    /**
     * Kriegsmarine M4 (4-rotor model)
     */
    const MODEL_KMM4 = 2;
}
