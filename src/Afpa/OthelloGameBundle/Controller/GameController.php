<?php

namespace Afpa\OthelloGameBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Afpa\OthelloGameBundle\Model\Board;
use Afpa\OthelloGameBundle\Entity\User;
use Afpa\OthelloGameBundle\Entity\Game;

class GameController extends Controller {

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

    /**
     * @Route("/game/list", name="game_list")
     * @Template()
     */
    public function gameListAction(Request $request) {
        $oSession = $request->getSession();

        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:Game');
        $aGames = $repo->findAll();


        return array(
            'games' => $aGames,
            'bIsConnected' => (!$oSession->get('oUser') instanceof User)
        );

        //redirection home? 
    }

    /**
     * @Route("/game/create", name="game_create")
     * @Template()
     */
    public function createAction(Request $request) {
        $oSession = $request->getSession();

        $oGame = new Game();
        $oGame->setName('');
        $oGame->setCreatedDate(new \DateTime('now'));
        $oGame->setEndGame(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($oGame);
        $em->flush();


        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:User');
        $oUser = $repo->findOneById($oSession->get('oUser')->getId());
        $oUser->setGame($oGame);
        $em->flush();

        return $this->redirect($this->generateUrl('game_list'));
    }

}
