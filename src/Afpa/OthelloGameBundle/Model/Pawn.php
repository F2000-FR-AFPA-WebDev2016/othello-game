<?php

namespace Afpa\OthelloGameBundle\Model;

/**
 * Pawn
 */
class Pawn {

    const TYPE_WHITE = 'white';
    const TYPE_BLACK = 'black';

    protected $color;

    public function __construct($color) {
        $this->color = $color;
    }

    public function __toString() {
        return ($this->color == self::TYPE_WHITE ) ? 'whitepawn.jpeg' : 'blackpawn.png';
    }

    public function reverse() {
        if ($color == self::TYPE_WHITE) {
            $color = self::TYPE_BLACK;
        } else {
            $color = self::TYPE_WHITE;
        }
    }

}
