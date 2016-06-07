<?php

namespace Afpa\OthelloGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Game {

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
     * @ORM\Column(name="party_name", type="string", length=150)
     */
    private $partyName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_hour", type="datetimetz")
     */
    private $dateHour;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255)
     */
    private $data;

    /**
     * @var boolean
     *
     * @ORM\Column(name="end_game", type="boolean")
     */
    private $endGame;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="game")
     */
    protected $users;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set partyName
     *
     * @param string $partyName
     * @return Game
     */
    public function setPartyName($partyName) {
        $this->partyName = $partyName;

        return $this;
    }

    /**
     * Get partyName
     *
     * @return string
     */
    public function getPartyName() {
        return $this->partyName;
    }

    /**
     * Set dateHour
     *
     * @param \DateTime $dateHour
     * @return Game
     */
    public function setDateHour($dateHour) {
        $this->dateHour = $dateHour;

        return $this;
    }

    /**
     * Get dateHour
     *
     * @return \DateTime
     */
    public function getDateHour() {
        return $this->dateHour;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return Game
     */
    public function setData($data) {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Set endGame
     *
     * @param boolean $endGame
     * @return Game
     */
    public function setEndGame($endGame) {
        $this->endGame = $endGame;

        return $this;
    }

    /**
     * Get endGame
     *
     * @return boolean
     */
    public function getEndGame() {
        return $this->endGame;
    }

}
