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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetimetz")
     */
    protected $createdDate;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255, nullable=true)
     */
    protected $data;

    /**
     * @var boolean
     *
     * @ORM\Column(name="end_game", type="boolean")
     */
    protected $endGame;

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

    /**
     * Constructor
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Game
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Game
     */
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     * Add users
     *
     * @param \Afpa\OthelloGameBundle\Entity\User $users
     * @return Game
     */
    public function addUser(\Afpa\OthelloGameBundle\Entity\User $users) {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Afpa\OthelloGameBundle\Entity\User $users
     */
    public function removeUser(\Afpa\OthelloGameBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers() {
        return $this->users;
    }

}