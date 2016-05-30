<?php

namespace Afpa\OthelloGameBundle\Model;

/**
 * Board
 */
class Board {

    protected $aBoard;

    public function __construct() {
        // initialisation du plateau
        $this->aBoard = array();
        for ($i = 0; $i < 8; $i++) {
            $this->aBoard[$i] = array();

            for ($j = 0; $j < 8; $j++) {
                $this->aBoard[$i][$j] = NULL;
            }
        }

        // insertion des pions de départ
        $this->aBoard[3][3] = new WhitePawn;
        $this->aBoard[3][4] = new BlackPawn;
        $this->aBoard[4][3] = new BlackPawn;
        $this->aBoard[4][4] = new WhitePawn;
    }

    public function getBoard() {
        return $this->aBoard;
    }

    public function setBoard($aBoard) {
        $this->aBoard = $aBoard;
    }

}
