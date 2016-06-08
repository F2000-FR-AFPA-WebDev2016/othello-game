<?php

namespace Afpa\OthelloGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;
use Afpa\OthelloGameBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function registerAction(Request $request) {
        $oUser = new User();

        $oForm = $this->createFormBuilder($oUser)
                ->add('login', 'text')
                ->add('password', 'password')
                ->getForm();

        if ($request->isMethod('POST')) {
            //récupérer les données
            $oForm->bind($request);

            if ($oForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //hash password
                $oUser->setPassword(User::cryptPwd($oUser->getPassword()));
                $oUser->setNbWinnedGame(0);
                $em->persist($oUser);
                $em->flush();

                return $this->redirect($this->generateUrl('home'));
            }
        }
        return array('register' => $oForm->createView());
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction(Request $request) {
        $oUser = new User ();

        $oForm = $this->createFormBuilder($oUser)
                ->add('login', 'text')
                ->add('password', 'password')
                ->getForm();

        if ($request->isMethod('POST')) {
            //récupérer les données
            $oForm->bind($request);

            if ($oForm->isValid()) {
                $repo = $this->getDoctrine()->getRepository('AfpaOthelloGameBundle:User');
                $oUserTemp = $repo->findOneByLogin($oUser->getLogin());
                //todo get password et verifier
                //
                //vérifie si les données sont valides :
                //utilise la fonction verifAuth (cf. User)
                if ($oUserTemp && $oUserTemp->verifAuth($oUser->getPassword())) {
                    //stockage session
                    echo 'je suis loggé';
                    $oSession = $request->getSession();
                    $oSession->set('oUser', $oUserTemp);
                    return $this->redirect($this->generateUrl('game_list'));
                }
            }
        }
        return array('login' => $oForm->createView());
    }

    /**
     * @Route("/logout", name="logout")
     * @Template()
     */
    public function logoutAction(Request $request) {
        $request->getSession()->clear();
        return $this->redirect($this->generateUrl('home'));
    }

}

?>