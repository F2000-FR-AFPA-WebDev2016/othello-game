<?php

namespace Afpa\OthelloGameBundle\Model;

/**
 * Board
 */
class Board {

    protected $aBoard;
    protected $aPossibleCases;
    protected $aDirection;
    protected $playerTurn;
    protected $scoreWhite;
    protected $scoreBlack;

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

    /* parcours de tout le board et stockage dans le tableau aPossibleCases
     * de toutes les cases (ligne et colonne) où il est possible de jouer
     */

    public function calculPossiblesCases() {
        $this->aPossibleCases = array();
        $this->aDirection = array();

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
          print_r($this->aDirection);
          echo '</pre>'; */
    }

    /**
     * Permet de savoir si la case est cliquable ou pas et remplissage en même
     * temps du tableau des directions (pas pour ligne et pas pour colonne) qui
     * permettra plus tard de retourner les pions.
     * @param type $l
     * @param type $c
     * @return boolean
     */
    public function isPossibleAction($l, $c) {
        /* echo '-----------' . '<br/>';
          echo 'isPossibleAction : ' . $l . ',' . $c . '<br />'; */
        $bPossible = false;
        /* echo ' couleur joueur: ' . $this->playerTurn . '<br />'; */
        if ($this->playerTurn == Pawn::TYPE_WHITE) {
            $OppositeColor = Pawn::TYPE_BLACK;
        } else {
            $OppositeColor = Pawn::TYPE_WHITE;
        }
        /* echo ' couleur opposée : ' . $OppositeColor . '<br />'; */

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

        // si on est dans un angle, comme on a enleve 2 fois la meme case
        // on rajoute une possibilite
        if (($l == 0 && $c == 0) || ($l == 0 && $c == 7) || ($l == 7 && $c == 0) || ($l == 7 && $c == 7)) {
            $iNbPos++;
        }
        /* echo '$iNbPos: ' . $iNbPos . '<br />';
          echo '$i : ' . $i . '<br />';
          echo '$j : ' . $j . '<br />';
          echo '$finL : ' . $FinL . '<br />';
          echo '$finC : ' . $FinC . '<br />'; */
        $jdeb = $j;
        while (!$bPossible && $iNbPos != 0 && $i < $FinL) {
            $j = $jdeb;
            while (!$bPossible && $iNbPos != 0 && $j < $FinC) {
                /*  echo 'ds les while' . '<br/>';
                  echo '$i : ' . $i . '<br />';
                  echo '$j : ' . $j . '<br />';
                  echo '$l : ' . $l . '<br />';
                  echo '$c : ' . $c . '<br />'; */
                if ($i != $l || $j != $c) {
                    // echo 'ds le if' . '<br/>';
                    if ($this->aBoard[$i][$j] instanceof Pawn && $this->aBoard[$i][$j]->getColor() == $OppositeColor) {
                        if ($this->run($l, $c, $i, $j)) {
                            // TODO : initialiser le tableau proprement
                            $this->aDirection[$l][$c][] = array($i - $l, $j - $c);
                            $bPossible = true;
                        }
                    }
                    $iNbPos--;
                }
                $j++;
            }
            $i++;
        }
        /* echo 'fin des whiles' . '<br />';
          echo '$i : ' . $i . '<br />';
          echo '$j : ' . $j . '<br />';
          if (!$bPossible) {
          echo 'false' . '<br />';
          } else {
          echo 'true' . '<br />';
          }
          echo 'inbpos : ' . $iNbPos . '<br />'; */

        return $bPossible;
    }

    public function validPosition($i, $j) {
        return $i > -1 && $i < 8 && $j > -1 && $j < 8;
    }

    /* on avance dans une direction jusqu'a trouver un pion de la même couleur
     * que celle du joueur --> true ou tomber sur une case vide ou le bord du board
     * --> false
     */

    public function run($l, $c, $Li, $Co) {
        $StepL = $Li - $l;
        $StepC = $Co - $c;
        /* echo 'ds fonction run' . '<br/>';
          echo '$L : ' . $l . '<br/>';
          echo '$C : ' . $c . '<br/>';
          echo '$Li : ' . $Li . '<br/>';
          echo '$Co : ' . $Co . '<br/>';
          echo '$StepL : ' . $StepL . '<br/>';
          echo '$StepC : ' . $StepC . '<br/>'; */

        $bPossible = false;
        $i = $Li;
        $j = $Co;

        while ($this->validPosition($i, $j) && $this->aBoard[$i][$j] instanceof Pawn && $this->aBoard[$i][$j]->getColor() != $this->playerTurn) {
            /* echo '$i : ' . $i . '<br/>';
              echo '$j : ' . $j . '<br/>';
              echo 'couleur pion ' . $this->aBoard[$i][$j]->getColor() . '<br/>'; */

            $j += $StepC;
            $i += $StepL;
        }
        //echo 'out' . '<br/>';
        if ($this->aBoard[$i][$j] instanceof Pawn && $this->aBoard[$i][$j]->getColor() == $this->playerTurn) {
            // echo 'true' . '<br/>';
            $bPossible = true;
        }

        return $bPossible;
    }

    /*
     * Permet de savoir si la case est cliquable ou pas
     */

    public function possibleClick($l, $c) {
        return in_array(array($l, $c), $this->aPossibleCases);
    }

    /* 7
     * on met à jour le board en retournant tous les pions possibles
     */

    public function turnPawn($l, $c) {
        echo '<pre>';
        print_r($this->aDirection[$l][$c]);
        echo '</pre>';

        if ($this->possibleClick($l, $c)) {
            foreach ($this->aDirection[$l][$c] as $way) {
                $StepL = $way[0];
                $StepC = $way[1];
                $i = $l + $StepL;
                $j = $c + $StepC;
                while ($this->validPosition($i, $j) && $this->aBoard[$i][$j]->getColor() != $this->playerTurn) {
                    $this->aBoard[$i][$j]->reverse();
                    if ($this->playerTurn == Pawn::TYPE_WHITE) {
                        $this->scoreWhite++;
                        $this->scoreBlack--;
                    } else {
                        $this->scoreBlack++;
                        $this->scoreWhite--;
                    }

                    $j += $StepC;
                    $i += $StepL;
                }
            }
        }
    }

    public function doAction($l, $c) {
        $bSuccess = 'error';

        //echo'nouvelle partie';
        // TODO.intégrer les actions de Martine.
        if (!$this->aBoard[$l][$c] instanceof Pawn) {
            if ($this->possibleClick($l, $c)) {
                $this->aBoard[$l][$c] = new Pawn($this->playerTurn);
                $this->TurnPawn($l, $c);

                $aBackup = $this->aDirection;
                $this->nextPlayer();
                $bSuccess = 'success';
            }
        }

        return array(
            'status' => $bSuccess,
            'data' => $aBackup
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
        $this->calculPossiblesCases();
    }

    public function getBoard() {
        return $this->aBoard;
    }

    public function setBoard($aBoard) {
        $this->aBoard = $aBoard;
    }

}
?>