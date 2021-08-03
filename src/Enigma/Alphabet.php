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
 * This class represents the character set for the enigma capital A - Z.
 *
 * @package Enigma
 */
class Alphabet implements AlphabetInterface
{
    /**
     * encoding table
     */
    private $flippedAlphabet;
    private $englishAlphabet;

    /**
     *
     */
    public function __construct()
    {
        $this->englishAlphabet = range('A', 'Z');
        $this->flippedAlphabet = array_flip($this->englishAlphabet);
    }

    /**
     * @return int
     */
    public function alphabetSize(): int
    {
        return count($this->englishAlphabet);
    }

    /**
     * Converts character to number
     *
     * @param string $englishCharacter
     * @return int
     * @throws AlphabetException
     */
    public function toEnigma(string $englishCharacter): int
    {
        if (isset($this->flippedAlphabet[$englishCharacter])) {
            return $this->flippedAlphabet[$englishCharacter];
        }

        throw new AlphabetException('No corresponding character');
    }

    /**
     * Converts number to character
     *
     * @param string $enigmaCharacter
     * @return string
     * @throws AlphabetException
     */
    public function fromEnigma(string $enigmaCharacter): string
    {
        if (isset($this->englishAlphabet[$enigmaCharacter])) {
            return $this->englishAlphabet[$enigmaCharacter];
        }

        throw new AlphabetException('No corresponding character');
    }
}
