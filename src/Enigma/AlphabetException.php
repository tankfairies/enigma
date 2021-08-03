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

use Exception;

/**
 * Class AlphabetException
 *
 * @package Enigma
 */
class AlphabetException extends Exception
{

    public function __construct($message, $code = 0, Exception $previous = null)
    {

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}
