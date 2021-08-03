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
 * Interface WiringInterface
 *
 * @package Enigma
 */
interface WiringInterface
{
    public function setup(AlphabetInterface $alphabet, string $wiring): self;
    public function connect(int $pin1, int $pin2): void;
    public function connectsTo(int $pin): int;
    public function processLetter1stPass(int $pin): int;
    public function processLetter2ndPass(int $pin): int;
}