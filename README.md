[![Latest Stable Version](https://poser.pugx.org/tankfairies/enigma/v/stable)](https://packagist.org/packages/tankfairies/enigma)
[![Total Downloads](https://poser.pugx.org/tankfairies/enigma/downloads)](https://packagist.org/packages/tankfairies/enigma)
[![Latest Unstable Version](https://poser.pugx.org/tankfairies/enigma/v/unstable)](https://packagist.org/packages/tankfairies/enigma)
[![License](https://poser.pugx.org/tankfairies/enigma/license)](https://packagist.org/packages/tankfairies/enigma)
[![Build Status](https://travis-ci.com/tankfairies/enigma.svg?branch=master)](https://travis-ci.com/tankfairies/enigma)


# Enigma

## Installation

Install with [Composer](https://getcomposer.org/):

```bash
composer require tankfairies/enigma
```
## Details
This package provides the funtionality of 3 different Enigma models:

*   Wehrmacht / Luftwaffe 3 rotor model
*   Kriegsmarine 3 rotor model
*   Kriegsmarine 4 rotor model

Each model can be equipped with a different set of rotors and refelctors. All in all are 10 types of rotors and 4 types of refelctors available.

*   Wehrmacht / Luftwaffe 3 rotor model uses:
    *   rotors: I, II, III, IV, V
    *   reflectors: B, C
*   Kriegsmarine 3 rotor model uses:
    *   rotors: I, II, III, IV, V, VI, VII, VIII
    *   reflectors: B, C
*   Kriegsmarine 4 rotor model uses:
    *   rotors: I, II, III, IV, V, VI, VII, VIII, Beta, Gamma
    *   reflectors: B Thin, C Thin

Each rotor and reflector provides a unique wiring, which can not be changed. Settings are:

*   Contacts = ABCDEFGHIJKLMNOPQRSTUVWXYZ
*   I = EKMFLGDQVZNTOWYHXUSPAIBRCJ
*   II = AJDKSIRUXBLHWTMCQGZNPYFVOE
*   III = BDFHJLCPRTXVZNYEIWGAKMUSQO
*   IV = ESOVPZJAYQUIRHXLNFTGKDCMWB
*   V = VZBRGITYUPSDNHLXAWMJQOFECK
*   VI = JPGVOUMFYQBENHZRDKASXLICTW
*   VII = NZJHGRCXMYSWBOUFAIVLPEKQDT
*   VIII = FKQHTLXOCBJSPDZRAMEWNIUYGV
*   Beta = LEYJVCNIXWPBQMDRTAKZGFUHOS
*   Gamma = FSOKANUERHMBTIYCWLQPZXVGJD
*   B = YRUHQSLDPXNGOKMIEBFZCWVJAT
*   C = FVPJIAOYEDRZXWGCTKUQSBNMHL
*   B Thin = ENKQAUYWJICOPBLMDXZVFTHRGS
*   C Thin = RDOBJNTKVEHMLFCWZAXGYIPSUQ
*   Contacts = ABCDEFGHIJKLMNOPQRSTUVWXYZ

Rotors can have notches, which indicate the position where the next rotor is advanced. e.g.: Notch at position Q means, if rotor steps from Q to R, the next rotor is advanced. These positions are:

*   I = Q
*   II = E
*   III = V
*   IV = J
*   V = Z
*   VI, VII, VIII = Z + M

Each Rotor can be only used in one position at a time. Rotors I..VIII can be mounted at position 1, 2 or 3, wherelse rotors Beta and Gamma can only be used at position 4\. Aditionally, Beta and Gamma can only be used in combination with reflector B Thin or C Thin, the others only with reflector B or C.

**IMPORTANT**

These conditions only apply if a proper emulation of the original Enigma is desired. This implementation allows to setup the rotors in any order, so its up to the user to take care of the order of rotors.

## Usage

Instantiate a new instance of the library:

### Reflectors
```php
ReflectorInterface::REFLECTOR_B
ReflectorInterface::REFLECTOR_C
ReflectorInterface::REFLECTOR_BTHIN
ReflectorInterface::REFLECTOR_CTHIN
```

### Rotors
```php
RotorInterface::ROTOR_I
RotorInterface::ROTOR_II
RotorInterface::ROTOR_III
RotorInterface::ROTOR_IV
RotorInterface::ROTOR_V
RotorInterface::ROTOR_VI
RotorInterface::ROTOR_VII
RotorInterface::ROTOR_VIII
RotorInterface::ROTOR_BETA
RotorInterface::ROTOR_GAMMA
```

### Code Example
```php
use Tankfairies\Enigma\Enigma;

$enigma = new Enigma();
$enigma
    ->installRotors(new Rotor())
    ->installReflector(new Reflector())
    ->installWiring(new Wiring())
    ->installPlugboard(new Plugboard())
    ->installAplhabet(new Enigma\Alphabet());

$enigma
    ->setModel(EnigmaInterface::MODEL_KMM3)
    ->setRotors([
        RotorInterface::ROTOR_I,
        RotorInterface::ROTOR_II,
        RotorInterface::ROTOR_III
    ])
    ->setReflector(ReflectorInterface::REFLECTOR_B)
    ->initialise();

$this->enigma->setPosition(RotorInterface::ROTOR_I, "Q");
$this->enigma->setPosition(RotorInterface::ROTOR_II, "E");
$this->enigma->setPosition(RotorInterface::ROTOR_III, "V");

$this->enigma->setRingstellung(RotorInterface::ROTOR_I, "A");
$this->enigma->setRingstellung(RotorInterface::ROTOR_II, "A");
$this->enigma->setRingstellung(RotorInterface::ROTOR_III, "A");

$this->enigma->plugLetters("B", "Q");
$this->enigma->plugLetters("C", "R");
$this->enigma->plugLetters("D", "I");
$this->enigma->plugLetters("E", "J");
$this->enigma->plugLetters("K", "W");
$this->enigma->plugLetters("M", "T");
$this->enigma->plugLetters("O", "S");
$this->enigma->plugLetters("P", "X");
$this->enigma->plugLetters("U", "Z");
$this->enigma->plugLetters("G", "H");

$message = str_split("HELLOXWORLD");

$encoded = '';
foreach ($message as $character) {
    $encoded .= $this->enigma->encodeLetter($character);
}
```

## Further Reading
[http://en.wikipedia.org/wiki/Enigma_machine](http://en.wikipedia.org/wiki/Enigma_machine)

[http://users.telenet.be/d.rijmenants/](http://users.telenet.be/d.rijmenants/)

## Credit

Thanks to Rafal Masiarek from Mustache Lab, as this was the inspiriation for this project.
https://github.com/MustacheLab/PHP-Enigma

## Copyright and license

The tankfairies/rulesengine library is Copyright (c) 2021 Tankfairies (https://tankfairies.com) and licensed for use under the MIT License (MIT).
