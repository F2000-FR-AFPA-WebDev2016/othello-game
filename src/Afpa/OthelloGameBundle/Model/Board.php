<?php

namespace Afpa\OthelloGameBundle\Model;

/**
 * Board
 */
class Board {

    protected $aBoard;
    protected $aPossibleCases;
    protected $playerTurn;

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
        /* $this->aBoard[3][3] = new WhitePawn;
          $this->aBoard[3][4] = new BlackPawn;
          $this->aBoard[4][3] = new BlackPawn;
          $this->aBoard[4][4] = new WhitePawn; */

// initialisation du tableau des cases cliquables

        $this->aBoard[3][3] = new Pawn(Pawn::TYPE_WHITE);
        $this->aBoard[3][4] = new Pawn(Pawn::TYPE_BLACK);
        $this->aBoard[4][3] = new Pawn(Pawn::TYPE_BLACK);
        $this->aBoard[4][4] = new Pawn(Pawn::TYPE_WHITE);

        $this->playerTurn = Pawn::TYPE_BLACK;
        $this->calculPossiblesCases();
    }

    public function calculPossiblesCases() {
        $this->aPossibleCases = array();

        $k = 0;
        for ($i = 0; $i < 8; $i++) {

            $this->aPossibleCases[$k] = array();

            for ($j = 0; $j < 8; $j++) {
                $this->aPossibleCases[$k][0] = $i;
                $this->aPossibleCases[$k][1] = $j;
                $k++;
                if ($k % 8 == 0) {
                    $k--;
                }
            }
            $k++;
        }
        /* echo '<pre>';
          echo 'tableau des cas possibles : ';
          print_r($this->aPossibleCases);
          echo '</pre>'; */
    }

    public function PossibleCases() {
        $aPossibleCase = array();
        $l = 0;
        $c = 0;
        $k = 0;

        while ($l < 8) {
            while ($c < 8) {
                if (IsPossibleAction($l, $c)) {

                }
            }
        }
    }

    public function IsPossibleAction($l, $c, $PawnColor) {
        $bPossible = false;
        if ($PawnColor == 'white') {
            $OppositeColor = 'black';
        } else {
            $OppositeColor = 'white';
        }
        $iNbPos = 8;
        if ($l > 0) {
            $i = $l - 1;
        } else {
            $i = $l;
            $iNbPos = $iNbPos - 3;
        }
        if (c > 0) {
            $j = $c - 1;
            $iNpPos = $iNbPos - 3;
        }
        if ($l == 7) {
            $FinL = $l + 1;
            $iNbPos = $iNbPos - 3;
        } else {
            $FinL = $l + 2;
        }
        if ($c == 7) {
            $FinC = $c + 1;
            $iNbPos = $iNbPos - 3;
        } else {
            $Finc = $c + 2;
        }
        if (($l == 0 && c == 0) || ($l == 0 && $c == 7) || ($l == 7 && $c == 0) || ($l == 7 && $c == 7)) {
            $iNbPos++;
        }
        $Way = 0;
        while (!bPossible && $iNbPos != 0 && $i < $FinL) {
            while (!Possible && $iNbPos != 0 && $j < $FinC) {
                if ($i != $l && $j != $c) {
                    if ($aBoard[$i][$j] == $OppositeColor) {
                        if (Run($l, $c, $i, $j, $PawnColor)) {
                            $aDirection[$l][$c][$Way][0] = $l - $i;
                            $aDirection[$l][$c][$Way][1] = $c - $j;
                            $Way++;
                            $bPossible = true;
                        }
                    }
                    $iNbPos--;
                }
                $j++;
            }
            $i++;
        }
        return $bPossible;
    }

    public function Run($l, $c, $Li, $Co, $PawnColor) {
        $PasL = $l - $Li;
        $PasC = $c - $Co;
        $bPossible = false;
        $i = 0;
        $j = 0;
        while (!bPossible && $i > -1 && $i < 8) {
            while (!bPossible && $j > -1 && j < 8) {
                if ($aBoard[$i][$j] == $PawnColor) {
                    $bPossible = true;
                }
                $j = $j + $PasC;
            }
            $i = $i + $PasL;
        }
        return $bPossible;
    }

    public function getPlayerTurn() {
        if ($this->playerTurn == Pawn::TYPE_BLACK) {
            return 'Joueur Noir';
        } else {
            return 'Joueur Blanc';
        }
    }

    public function getBoard() {
        return $this->aBoard;
    }

    public function setBoard($aBoard) {
        $this->aBoard = $aBoard;
    }

}
