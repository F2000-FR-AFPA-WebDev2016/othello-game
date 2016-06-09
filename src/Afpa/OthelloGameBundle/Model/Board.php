<?php

namespace Afpa\OthelloGameBundle\Model;

use Afpa\OthelloGameBundle\Entity\Game;

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
    protected $playerBlack;
    protected $playerWhite;

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
        $this->scoreWhite = $this->scoreBlack = 2;

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
    }

    /**
     * Permet de savoir si la case est cliquable ou pas et remplissage en même
     * temps du tableau des directions (pas pour ligne et pas pour colonne) qui
     * permettra plus tard de retourner les pions.
     *
     * @param type $l
     * @param type $c
     * @return boolean
     */
    public function isPossibleAction($l, $c) {
        if ($this->aBoard[$l][$c] instanceof Pawn) {
            return false;
        }

        if ($this->playerTurn == Pawn::TYPE_WHITE) {
            $OppositeColor = Pawn::TYPE_BLACK;
        } else {
            $OppositeColor = Pawn::TYPE_WHITE;
        }
        $this->aDirection[$l][$c] = array();
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

        $jdeb = $j;
        while ($iNbPos != 0 && $i < $FinL) {
            $j = $jdeb;
            while ($iNbPos != 0 && $j < $FinC) {
                if ($i != $l || $j != $c) {
                    // echo 'ds le if' . '<br/>';
                    if ($this->aBoard[$i][$j] instanceof Pawn && $this->aBoard[$i][$j]->getColor() == $OppositeColor) {
                        if ($this->run($l, $c, $i, $j)) {
                            // TODO : initialiser le tableau proprement
                            $this->aDirection[$l][$c][] = array($i - $l, $j - $c);
                        }
                    }
                    $iNbPos--;
                }
                $j++;
            }
            $i++;
        }
        return count($this->aDirection[$l][$c]) > 0;
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

        $bPossible = false;
        $i = $Li;
        $j = $Co;

        while ($this->validPosition($i, $j) && $this->aBoard[$i][$j] instanceof Pawn && $this->aBoard[$i][$j]->getColor() != $this->playerTurn) {
            $j += $StepC;
            $i += $StepL;
        }

        if ($this->validPosition($i, $j) && $this->aBoard[$i][$j] instanceof Pawn && $this->aBoard[$i][$j]->getColor() == $this->playerTurn) {
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
        foreach ($this->aDirection[$l][$c] as $way) {
            $StepL = $way[0];
            $StepC = $way[1];

            $i = $l + $StepL;
            $j = $c + $StepC;
            while ($this->validPosition($i, $j) && $this->aBoard[$i][$j]->getColor() != $this->playerTurn) {
                $this->aBoard[$i][$j]->reverse();
                //gestion des scores :
                if ($this->playerTurn == Pawn::TYPE_WHITE) {
                    $this->scoreWhite++;
                    $this->scoreBlack--;
                } else {
                    $this->scoreBlack++;
                    $this->scoreWhite--;
                }

                $i += $StepL;
                $j += $StepC;
            }
        }
    }

    public function doAction($l, $c, $idJoueur = null) {

        $bSuccess = 'error';
        $bUserOk = true;

        // TODO verif id joueur => idplayerblack/white sinon status errors
        if ($idJoueur != null) {
            $iUserValid = ($this->playerTurn == Pawn::TYPE_WHITE) ? $this->playerWhite : $this->playerBlack;
            $bUserOk = ($iUserValid == $idJoueur);
        }

        if ($bUserOk && $this->validPosition($l, $c) && !$this->aBoard[$l][$c] instanceof Pawn &&
                !$this->checkEndGame()) {
            if ($this->possibleClick($l, $c)) {
                $this->aBoard[$l][$c] = new Pawn($this->playerTurn);
                if ($this->playerTurn == Pawn::TYPE_WHITE) {
                    $this->scoreWhite++;
                } else {
                    $this->scoreBlack++;
                }


                $this->TurnPawn($l, $c);

                $this->nextPlayer();
                $bSuccess = 'success';
            }
        }

        return array(
            'status' => $bSuccess,
            'bEndGame' => $this->checkEndGame(),
            'possibilities' => $this->aPossibleCases
        );
    }

    public function nextPlayer() {
        if ($this->playerTurn == Pawn::TYPE_BLACK) {
            $this->playerTurn = Pawn::TYPE_WHITE;
        } else {
            $this->playerTurn = Pawn::TYPE_BLACK;
        }

        $this->calculPossiblesCases();
    }

    public function checkEndGame() {
        //cas à gérer
        //1-Plus de possibilité : impossible de jouer aPossibleCases=vide (on compte)
        //2-Plus de place sur board $aBoard completed (on compte les pions des 2 joueurs)
        if (count($this->aPossibleCases) == 0 || $this->scoreWhite + $this->scoreBlack == 64) {
            return true;
        }
    }

    public function setPlayers(Game $oGame) {
        $aPlayers = $oGame->getUsers()->toArray();
        if (count($aPlayers) == 2) {
            shuffle($aPlayers);
            $this->playerBlack = $aPlayers[0]->getId();
            $this->playerWhite = $aPlayers[1]->getId();
        }
    }

    public function getPlayerTurn() {
        if ($this->playerTurn == Pawn::TYPE_BLACK) {
            return 'Black';
        } else {
            return 'White';
        }
    }

    public function getWinner() {
        if ($this->scoreWhite == $this->scoreBlack) {
            return 'Egalité pour les 2 joueurs';
        }
        if ($this->scoreWhite > $this->scoreBlack) {
            return 'Le joueur blanc a gagné';
        } else {
            return 'Le joueur noir a gagné';
        }
    }

    public function getWinnerIds() {
        $aWinners = array();

        if ($this->playerWhite != null && $this->playerBlack != null) {
            if ($this->scoreWhite == $this->scoreBlack) {
                $aWinners[] = $this->playerWhite;
                $aWinners[] = $this->playerBlack;
            } elseif ($this->scoreWhite > $this->scoreBlack) {
                $aWinners[] = $this->playerWhite;
            } else {
                $aWinners[] = $this->playerBlack;
            }
        }

        return $aWinners;
    }

    public function getScoreBlack() {

        return $this->scoreBlack;
    }

    public function getScoreWhite() {

        return $this->scoreWhite;
    }

    public function getBoard() {
        return $this->aBoard;
    }

    public function setBoard($aBoard) {
        $this->aBoard = $aBoard;
    }

    public function getPlayerBlack() {
        return $this->playerBlack;
    }

    public function getPlayerWhite() {
        return $this->playerWhite;
    }

    public function setPlayerBlack($playerBlack) {
        $this->playerBlack = $playerBlack;
    }

    public function setPlayerWhite($playerWhite) {
        $this->playerWhite = $playerWhite;
    }

}

?>