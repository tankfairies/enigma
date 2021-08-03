<?php
/**
 * Copyright (c) 2021 Tankfairies
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/tankfairies/enigma
 */

namespace Tankfairies\Enigma\Plugboard;

use Tankfairies\Enigma\Wiring\WiringInterface;

/**
 * Interface PlugboardInterface
 *
 * @package Enigma
 */
interface PlugboardInterface
{
    public function installWiring(WiringInterface $wiring): self;
    public function processLetter(int $letter): int;
    public function plugLetters(int $letter1, int $letter2): void;
    public function unplugLetters(int $letter): void;
}
