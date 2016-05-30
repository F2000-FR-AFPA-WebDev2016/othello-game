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
        return array();
    }

    /**
     * @Route("/end", name="endGame")
     * @Template()
     */
    public function endGameAction() {
        return array();
    }

}
