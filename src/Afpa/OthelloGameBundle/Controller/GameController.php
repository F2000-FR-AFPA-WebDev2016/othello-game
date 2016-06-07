<?php

namespace Afpa\OthelloGameBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Afpa\OthelloGameBundle\Model\Board;

class GameController extends Controller {

    /**
     * @Route("/help", name="help")
     * @Template()
     */
    public function helpAction(Request $request) {
        /*
         * quand on le tableau des cas possibles est rempli
         * on affiche le bouton aide
         * si on a clique sur le bouton aide ->true
         * les cases cliquables clignotent
         * si on rappuie sur le bouton aide ->false les cases cliquables disparaissent
         */
    }

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function homeAction(Request $request) {
        //on garde en session le jeu
        $oSession = $request->getSession();

        $oGame = $oSession->get('game');
        if (!$oGame instanceof Board) {
            $oGame = new Board();
            $oSession->set('game', $oGame);
        }

        return array();
    }

    /**
     * @Route("/game/view", name="game")
     * @Template()
     */
    public function gameViewAction(Request $request) {
        //on garde en session le jeu
        $oSession = $request->getSession();
        $oGame = $oSession->get('game');

        return array(
            'board' => $oGame->getBoard(),
            'player' => $oGame->getPlayerTurn(),
            'scoreblack' => $oGame->getScoreBlack(),
            'scorewhite' => $oGame->getScoreWhite(),
        );
    }

    /**
     * @Route("/game/end", name="end")
     * @Template()
     */
    public function endGameAction(Request $request) {
        //on garde en session le jeu
        $oSession = $request->getSession();
        $oGame = $oSession->get('game');

        return array(
            'winner' => $oGame->getWinner(),
        );
    }

    /**
     * @Route("/game/action", name="game_action")
     * @Template()
     */
    //on crée une action pour obtenir l'url
    public function doAction(Request $request) {
        $oSession = $request->getSession();
        $oGame = $oSession->get('game');

        $l = $request->get('l');
        $c = $request->get('c');

        $aData = $oGame->doAction($l, $c);
        return new JsonResponse($aData);
    }

    /**
     * @Route("/game/reset", name="reset")
     * @Template()
     */
    public function gameResetAction(Request $request) {
        $oSession = $request->getSession();
        $oSession->set('game', new Board());

        return $this->redirect($this->generateUrl('home'));
    }

}
