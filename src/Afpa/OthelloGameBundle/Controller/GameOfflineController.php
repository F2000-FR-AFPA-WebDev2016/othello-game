<?php

namespace Afpa\OthelloGameBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Afpa\OthelloGameBundle\Model\Board;

class GameOfflineController extends Controller {

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function playAction(Request $request) {
//on garde en session le jeu
        $oSession = $request->getSession();

        $oBoard = $oSession->get('game');
        if (!$oBoard instanceof Board) {
            $oBoard = new Board();
            $oSession->set('game', $oBoard);
        }

        //Tableau : trier les 10 meilleurs joueurs par odre decroissant
        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:User');
        $aWinners = $repo->findBy(
                array(), // criterias
                array('nbWinnedGame' => 'desc'), // Tri
                10 // Limite
        );
        return array(
            'winners' => $aWinners,
        );
    }

    /**
     * @Route("/game/refresh")
     * @Template()
     */
    public function refreshAction(Request $request) {
        //on garde en session le jeu
        $oSession = $request->getSession();
        $oBoard = $oSession->get('game');

        return array(
            'board' => $oBoard->getBoard(),
            'player' => $oBoard->getPlayerTurn(),
            'scoreblack' => $oBoard->getScoreBlack(),
            'scorewhite' => $oBoard->getScoreWhite(),
        );
    }

    /**
     * @Route("/game/end")
     * @Template()
     */
    public function endAction(Request $request) {
        //on garde en session le jeu
        $oSession = $request->getSession();
        $oBoard = $oSession->get('game');

        return array(
            'winner' => $oBoard->getWinner(),
        );
    }

    /**
     * @Route("/game/action")
     * @Template()
     */
    //on crée une action pour obtenir l'url
    public function doAction(Request $request) {
        $oSession = $request->getSession();
        $oBoard = $oSession->get('game');

        $l = $request->get('l');
        $c = $request->get('c');

        $aData = $oBoard->doAction($l, $c);
        return new JsonResponse($aData);
    }

    /**
     * @Route("/game/reset", name="reset_session")
     * @Template()
     */
    public function resetAction(Request $request) {
        $oSession = $request->getSession();
        $oSession->set('game', new Board());

        return $this->redirect($this->generateUrl('home'));
    }

}
