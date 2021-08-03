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
 * Interface AlphabetInterface
 *
 * @package Enigma
 */
interface AlphabetInterface
{
    public const ENIGMA_KEY_A = 0;
    public const ENIGMA_KEY_B = 1;
    public const ENIGMA_KEY_C = 2;
    public const ENIGMA_KEY_D = 3;
    public const ENIGMA_KEY_E = 4;
    public const ENIGMA_KEY_F = 5;
    public const ENIGMA_KEY_G = 6;
    public const ENIGMA_KEY_H = 7;
    public const ENIGMA_KEY_I = 8;
    public const ENIGMA_KEY_J = 9;
    public const ENIGMA_KEY_K = 10;
    public const ENIGMA_KEY_L = 11;
    public const ENIGMA_KEY_M = 12;
    public const ENIGMA_KEY_N = 13;
    public const ENIGMA_KEY_O = 14;
    public const ENIGMA_KEY_P = 15;
    public const ENIGMA_KEY_Q = 16;
    public const ENIGMA_KEY_R = 17;
    public const ENIGMA_KEY_S = 18;
    public const ENIGMA_KEY_T = 19;
    public const ENIGMA_KEY_U = 20;
    public const ENIGMA_KEY_V = 21;
    public const ENIGMA_KEY_W = 22;
    public const ENIGMA_KEY_X = 23;
    public const ENIGMA_KEY_Y = 24;
    public const ENIGMA_KEY_Z = 25;

    public function alphabetSize(): int;
    public function toEnigma(string $englishCharacter): int;
    public function fromEnigma(string $enigmaCharacter): string;
}
