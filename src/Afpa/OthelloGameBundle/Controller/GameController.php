<?php

namespace Afpa\OthelloGameBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GameController extends Controller {

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function homeAction() {
        $oBoard = new \Afpa\OthelloGameBundle\Model\Board();

        return array(
            'board' => $oBoard->getBoard()
        );
    }

}
