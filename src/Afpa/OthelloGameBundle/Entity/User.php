<?php

namespace Afpa\OthelloGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=50)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_winned_game", type="integer")
     */
    protected $nbWinnedGame;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="users")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    protected $game;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login) {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    //verif password :
    public function verifAuth($password) {
        return (self::cryptPwd($password) == $this->getPassword());
    }

    public static function cryptPwd($sPwd) {
        return sha1('RX]D*-_pQUn(_\Xi#T$*' . $sPwd);
    }

    /**
     * Set game
     *
     * @param \Afpa\OthelloGameBundle\Entity\Game $game
     * @return User
     */
    public function setGame(\Afpa\OthelloGameBundle\Entity\Game $game = null) {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \Afpa\OthelloGameBundle\Entity\Game
     */
    public function getGame() {
        return $this->game;
    }

    /**
     * Set nb_winned_game
     *
     * @param integer $nbWinnedGame
     * @return User
     */
    public function setNbWinnedGame($nbWinnedGame) {
        $this->nbWinnedGame = $nbWinnedGame;

        return $this;
    }

    /**
     * Get nb_winned_game
     *
     * @return integer
     */
    public function getNbWinnedGame() {
        return $this->nbWinnedGame;
    }

}
