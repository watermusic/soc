<?php

namespace SOC\Bundle\SocBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="soc_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Score", mappedBy="player")
     */
    protected $scores;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="user")
     */
    protected $players;


    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->players = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * @param Score $score
     * @return $this
     */
    public function addScore(Score $score)
    {
        if (!$this->scores->contains($score)) {
            $score->setPlayer($this);
            $this->scores->add($score);
        }
        return $this;
    }

    /**
     * @param Score $score
     * @return $this
     */
    public function removeScore(Score $score)
    {
        if ($this->scores->contains($score)) {
            $score->setPlayer(null);
            $this->scores->removeElement($score);
        }
        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayer()
    {
        return $this->players;
    }

    /**
     * @param Player $player
     * @return $this
     */
    public function addPlayer(Player $player)
    {
        if (!$this->players->contains($player)) {
            $player->setUser($this);
            $this->players->add($player);
        }
        return $this;
    }

    /**
     * @param Player $player
     * @return $this
     */
    public function removePlayer(Player $player)
    {
        if ($this->players->contains($player)) {
            $player->setUser(null);
            $this->players->removeElement($player);
        }
        return $this;
    }

}