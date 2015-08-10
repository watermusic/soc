<?php

namespace SOC\Bundle\SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Score
 *
 * @Serializer\ExclusionPolicy("all")
 *
 * @ORM\Table(name="soc_lineup")
 * @ORM\Entity(repositoryClass="SOC\Bundle\SocBundle\Entity\LineupRepository")
 */
class Lineup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="lineups")
     **/
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="matchday", type="smallint")
     *
     * @Serializer\Expose
     */
    private $matchday;

    /**
     * @var integer
     *
     * @ORM\Column(name="data", type="json_array")
     *
     * @Serializer\Expose
     */
    private $data;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Lineup
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set matchday
     *
     * @param integer $matchday
     * @return Lineup
     */
    public function setMatchday($matchday)
    {
        $this->matchday = $matchday;

        return $this;
    }

    /**
     * Get matchday
     *
     * @return integer 
     */
    public function getMatchday()
    {
        return $this->matchday;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return Lineup
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return integer 
     */
    public function getData()
    {
        return $this->data;
    }
}
