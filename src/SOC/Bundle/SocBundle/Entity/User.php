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

    public function __construct()
    {
        $this->scores = new ArrayCollection();
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

}