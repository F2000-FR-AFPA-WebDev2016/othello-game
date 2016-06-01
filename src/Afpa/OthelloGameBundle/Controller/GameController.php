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
     * @Route("/", name="home")
     * @Template()
     */
    public function homeAction(Request $request) {
        //on garde en session le jeu
        $oSession = $request->getSession();

        $oGame = $oSession->get('game');
        $oGame = null;
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
            'player' => $oGame->getPlayerTurn()
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

        $x = $request->get('x');
        $y = $request->get('y');

        $aData = $oGame->doAction($x, $y);
        return new JsonResponse($aData);
    }

    /**
     * @Route("/game/reset", name="reset")
     * @Template()
     */
    public function gameResetAction(Request $request) {
        $oSession = $request->getSession();
        $oSession->set('game', new Board());

        return new JsonResponse();
    }

}
