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

class GameOnlineController extends Controller {

    /**
     * @Route("/game/list", name="game_list")
     * @Template()
     */
    public function listAction(Request $request) {
        $oSession = $request->getSession();

        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:Game');
        $aGames = $repo->findAll();

        $oGame = null;
        if ($oSession->get('oUser', null) instanceof User) {
            $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:User');
            $oUser = $repo->find($oSession->get('oUser')->getId());
            $oGame = $oUser->getGame();

            if ($oGame instanceof Game && $oGame->getStatus() == Game::STATUS_STARTED) {
                return $this->redirect($this->generateUrl('game_play', array('idGame' => $oGame->getId())));
            }
        }

        return array(
            'games' => $aGames,
            'game_user' => $oGame,
            'bIsConnected' => ($oSession->get('oUser') instanceof User)
        );

        //redirection home?
    }

    /**
     * @Route("/game/create", name="game_create")
     * @Template()
     */
    public function createAction(Request $request) {
        $oSession = $request->getSession();

        // Si tu n'es pas connecté, redirigé vers la page home
        if (!$oSession->get('oUser', null) instanceof User) {
            return $this->redirect($this->generateUrl('game_list'));
        }

        $oGame = new Game();
        $oGame->setName('');
        $oGame->setCreatedDate(new \DateTime('now'));
        $oGame->setStatus(Game::STATUS_WAITING);

        $em = $this->getDoctrine()->getManager();
        $em->persist($oGame);
        $em->flush();


        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:User');
        $oUser = $repo->find($oSession->get('oUser')->getId());
        $oUser->setGame($oGame);
        //execute les requetes
        $em->flush();

        return $this->redirect($this->generateUrl('game_list'));
    }

    /**
     * @Route("/game/join/{idGame}", name="game_join")
     * @Template()
     */
    public function joinAction(Request $request, $idGame) {
        $oSession = $request->getSession();

        // Si tu n'es pas connecté, redirigé vers la page home
        if (!$oSession->get('oUser', null) instanceof User) {
            return $this->redirect($this->generateUrl('game_list'));
        }

        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:Game');
        $oGame = $repo->find($idGame);

        //securisé -> utiliser instanceof de la classe
        if ($oGame instanceof Game) {
            $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:User');
            $oUser = $repo->find($oSession->get('oUser')->getId());
            $oUser->setGame($oGame);
            //execute les requetes
            $this->getDoctrine()->getManager()->flush();

            // verifier si la partie est complète ?
            // si oui, démarrage

            if (count($oGame->getUsers()) == 2) {
                $oBoard = new Board;
                $oBoard->setPlayers($oGame);
                $oGame->setStatus(Game::STATUS_STARTED);
                $oGame->setData(serialize($oBoard));
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('game_play', array('idGame' => $oGame->getId())));
            }
        }
        return $this->redirect($this->generateUrl('game_list'));
    }

    /**
     * @Route("/game/play/{idGame}", name="game_play")
     * @Template()
     */
    public function playAction($idGame) {
        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:Game');
        $oGame = $repo->findOneBy(array(
            'id' => $idGame,
            'status' => Game::STATUS_STARTED
        ));

        //Si game n'est pas une instance et si la partie n'a pas commencé : tu rediriges vers game_list
        if (!$oGame instanceof Game) {
            return $this->redirect($this->generateUrl('game_list'));
        }

        //recup id refresh
        return array(
            'idGame' => $idGame
        );
    }

    /**
     * @Route("/game/refresh/{idGame}", name="game_refresh")
     * @Template()
     */
    public function refreshAction($idGame) {
        $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:Game');
        $oGame = $repo->findOneBy(array(
            'id' => $idGame,
            'status' => Game::STATUS_STARTED
        ));

        //Si game n'est pas une instance et si la partie n'a pas commencé : tu rediriges vers game_list
        if (!$oGame instanceof Game) {
            return $this->redirect($this->generateUrl('game_list'));
        }

        // contraire : $oGame->setData(serialize($oBoard));
        $oBoard = unserialize($oGame->getData());


        return array(
            'idGame' => $idGame,
            'board' => $oBoard->getBoard(),
            'player' => $oBoard->getPlayerTurn(),
            'scoreblack' => $oBoard->getScoreBlack(),
            'scorewhite' => $oBoard->getScoreWhite(),
        );
    }

    /**
     * @Route("/game/action/{idGame}")
     * @Template()
     */
    public function gameAction(Request $request) {
        /* $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:Game');
          $oGame = $repo->findOneBy(array(
          'id' => $idGame,
          'status' => Game::STATUS_STARTED
          ));

          $oSession = $request->getSession();
          $oBoard = $oSession->get('game');

          $l = $request->get('l');
          $c = $request->get('c');

          $aData = $oBoard->doAction($l, $c);
          return new JsonResponse($aData); */
    }

}
