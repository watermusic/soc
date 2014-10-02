<?php

namespace SOC\Bundle\SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="soc_player",indexes={@ORM\Index(columns={"name"})})
 * @ORM\Entity(repositoryClass="SOC\Bundle\SocBundle\Entity\PlayerRepository")
 */
class Player
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="verein", type="string", length=255)
     */
    private $verein;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255)
     */
    private $position;

    /**
     * @var float
     *
     * @ORM\Column(name="vk_preis", type="float")
     */
    private $vkPreis;

    /**
     * @var float
     *
     * @ORM\Column(name="ek_preis", type="float")
     */
    private $ekPreis;

    /**
     * @var string
     *
     * @ORM\Column(name="kaeufer", type="string", length=255)
     */
    private $kaeufer;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float")
     */
    private $note;

    /**
     * @var float
     *
     * @ORM\Column(name="punkte", type="float")
     */
    private $punkte;


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
     * Set name
     *
     * @param string $name
     * @return Player
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set verein
     *
     * @param string $verein
     * @return Player
     */
    public function setVerein($verein)
    {
        $this->verein = $verein;

        return $this;
    }

    /**
     * Get verein
     *
     * @return string
     */
    public function getVerein()
    {
        return $this->verein;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Player
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set vkPreis
     *
     * @param float $vkPreis
     * @return Player
     */
    public function setVkPreis($vkPreis)
    {
        $this->vkPreis = $vkPreis;

        return $this;
    }

    /**
     * Get vkPreis
     *
     * @return float
     */
    public function getVkPreis()
    {
        return $this->vkPreis;
    }

    /**
     * Set ekPreis
     *
     * @param float $ekPreis
     * @return Player
     */
    public function setEkPreis($ekPreis)
    {
        $this->ekPreis = $ekPreis;

        return $this;
    }

    /**
     * Get ekPreis
     *
     * @return float
     */
    public function getEkPreis()
    {
        return $this->ekPreis;
    }

    /**
     * Set kaeufer
     *
     * @param string $kaeufer
     * @return Player
     */
    public function setKaeufer($kaeufer)
    {
        $this->kaeufer = $kaeufer;

        return $this;
    }

    /**
     * Get kaufer
     *
     * @return string
     */
    public function getKaeufer()
    {
        return $this->kaeufer;
    }

    /**
     * Set note
     *
     * @param float $note
     * @return Player
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return float
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set punkte
     *
     * @param float $punkte
     * @return Player
     */
    public function setPunkte($punkte)
    {
        $this->punkte = $punkte;

        return $this;
    }

    /**
     * Get punkte
     *
     * @return float
     */
    public function getPunkte()
    {
        return $this->punkte;
    }
}
