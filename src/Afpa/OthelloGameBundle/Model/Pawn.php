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

    // fct magique : __toString : methode affichage :
    public function __toString() {
        return ($this->color == self::TYPE_WHITE ) ? 'whitepawn.png' : 'blackpawn.png';
    }

    public function reverse() {
        if ($this->color == self::TYPE_WHITE) {
            $this->color = self::TYPE_BLACK;
        } else {
            $this->color = self::TYPE_WHITE;
        }
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
    }

}
