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

        for ($l = 0; $l < 8; $l++) {
            for ($c = 0; $c < 8; $c++) {

                if ($this->isPossibleAction($l, $c)) {
                    $this->aPossibleCases[] = array($l, $c);
                }
            }
        }
        /* echo '<pre>';
          echo 'tableau des cas possibles : ';
          print_r($this->aPossibleCases);
          echo '</pre>'; */
    }

    public function isPossibleAction($l, $c) {
        $bPossible = false;

        if ($this->playerTurn == Pawn::TYPE_WHITE) {
            $OppositeColor = Pawn::TYPE_BLACK;
        } else {
            $OppositeColor = Pawn::TYPE_WHITE;
        }
        $iNbPos = 8;

// calcul de i
        if ($l > 0) {
            $i = $l - 1;
        } else {
            $i = $l;
            $iNbPos = $iNbPos - 3;
        }

// calcul de j
        if ($c > 0) {
            $j = $c - 1;
        } else {
            $j = $c;
            $iNbPos = $iNbPos - 3;
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
            $FinC = $c + 2;
        }

        if (($l == 0 && $c == 0) || ($l == 0 && $c == 7) || ($l == 7 && $c == 0) || ($l == 7 && $c == 7)) {
            $iNbPos++;
        }

        $Way = 0;
        while (!$bPossible && $iNbPos != 0 && $i < $FinL) {
            while (!$bPossible && $iNbPos != 0 && $j < $FinC) {

                if ($i != $l && $j != $c) {
                    if ($this->aBoard[$i][$j] instanceof Pawn &&
                            $this->aBoard[$i][$j]->getColor() == $OppositeColor) {

                        if ($this->run($l, $c, $i, $j, $this->playerTurn)) {
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

    public function run($l, $c, $Li, $Co) {
        $PasL = $l - $Li;
        $PasC = $c - $Co;
        $bPossible = false;
        $i = 0;
        $j = 0;

        while (!$bPossible && $i > -1 && $i < 8) {
            while (!$bPossible && $j > -1 && $j < 8) {

                if ($this->aBoard[$i][$j] instanceof Pawn &&
                        $this->aBoard[$i][$j]->getColor() == $this->playerTurn) {
                    $bPossible = true;
                }

                $j = $j + $PasC;
            }
            $i = $i + $PasL;
        }

        return $bPossible;
    }

    public function possibleClick($l, $c) {
        $i = 0;
        $bTrouve = false;
        while (!$bTrouve && $i < count($this->aPossibleCases)) {
            if ($this->aPossibleCases[$i][0] == $l && $this->aPossibleCases[$i][1] == $c) {
                $bTrouve = true;
            }
            $i++;
        }
        return $bTrouve;
    }

    public function turnPawn($l, $c) {

        if ($this->possibleClick($l, $c)) {
            if ($this->playerTurn == Pawn::TYPE_WHITE) {
                $OppositeColor = Pawn::TYPE_BLACK;
            } else {
                $OppositeColor = Pawn::TYPE_WHITE;
            }
            $Way = 0;
            while ($Way < 8) {
                $StepL = $aDirection[$l][$c][$Way][0];
                $StepC = $aDirection[$l][$c][$Way][1];
                $i = $l + $StepL;
                $j = $c + $StepC;
                while ($i >= 0 && $i < 8) {
                    while ($j >= 0 && $j < 8 && $this->aBoard[$i][$j] == $OppositeColor) {
                        $this->aBoard[$i][$j] = $this->playerTurn;
                        $j = $j + $Stepc;
                    }
                    $i = $i + $stepL;
                }
            }
        }
    }

    public function doAction($x, $y) {
        // TODO.intégrer les actions de Martine.
        $this->aBoard[$x][$y] = new Pawn($this->playerTurn);
        $this->nextPlayer();

        return array(
            'status' => 'success'
        );
    }

    public function getPlayerTurn() {
        if ($this->playerTurn == Pawn::TYPE_BLACK) {
            return 'Black';
        } else {
            return 'White';
        }
    }

    public function nextPlayer() {
        if ($this->playerTurn == Pawn::TYPE_BLACK) {
            $this->playerTurn = Pawn::TYPE_WHITE;
        } else {
            $this->playerTurn = Pawn::TYPE_BLACK;
        }
    }

    public function getBoard() {
        return $this->aBoard;
    }

    public function setBoard($aBoard) {
        $this->aBoard = $aBoard;
    }

}

?>
