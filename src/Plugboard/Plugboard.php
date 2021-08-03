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

use Tankfairies\Enigma\Wiring\Wiring;
use Tankfairies\Enigma\Wiring\WiringInterface;
use Tankfairies\Enigma\Wiring\WiringException;

/**
 * This class represents the Plugboard of an Enigma.
 *
 * The initial setup looks like this:
 *
 * <pre>
 * A B C D E...
 * | | | | | |
 * A B C D E...
 * </pre>
 *
 * The wiring can be changed by the operator.
 * This is done by connecting 2 letters together, e.g. 'D' and 'F':
 *
 * <pre>
 * A B C D E...
 * | | | | | |
 * A B C F E...
 * </pre>
 *
 * unplugging 1 of the 2 letters reset the character pair
 *
 * @package Enigma
 */
class Plugboard implements PlugboardInterface
{

    /**
     * The wiring of the plugboard.
     *
     * Pins always have to be connected in pairs, that means, if 'D' on side A
     * connects to 'H' on side B, 'H' on side A has to connect to 'D' on side B
     * @var Wiring
     */
    private $wiring = null;

    /**
     * @param WiringInterface $wiring
     * @return PlugboardInterface
     */
    public function installWiring(WiringInterface $wiring): PlugboardInterface
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

    /**
     * Connect 2 letters.
     *
     * @param integer letter 1 to connect
     * @param integer letter 2 to connect
     * @return void
     * @throws WiringException
     */
    public function plugLetters(int $letter1, int $letter2): void
    {
        $this->wiring->connect($letter1, $letter2);
        $this->wiring->connect($letter2, $letter1);
    }

    /**
     * Disconnect 2 letters.
     * Because letters are connected in pairs, we only need to know one of them.
     *
     * @param integer 1 of the 2 letters to disconnect
     * @return void
     * @throws WiringException
     */
    public function unplugLetters(int $letter): void
    {
        $letterConnectsTo = $this->wiring->connectsTo($letter);

        $this->wiring->connect($letter, $letter);
        $this->wiring->connect($letterConnectsTo, $letterConnectsTo);
    }
}
