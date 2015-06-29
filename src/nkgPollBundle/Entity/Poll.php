<?php

namespace nkgPollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Poll
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="nkgPollBundle\Entity\PollRepository")
 */
class Poll
{
     /**
     * @ORM\OneToMany(targetEntity="Opinion", mappedBy="poll", cascade={"persist"})
     */
    protected $opinions;

    public function __construct()
    {
        $this->opinions = new ArrayCollection();
    }

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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", nullable=true, type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedat", type="datetime")
     */
    private $modifiedat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     */
    private $enddate;


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
     * Set libelle
     *
     * @param string $libelle
     * @return Poll
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Poll
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return Poll
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set modifiedat
     *
     * @param \DateTime $modifiedat
     * @return Poll
     */
    public function setModifiedat($modifiedat)
    {
        $this->modifiedat = $modifiedat;

        return $this;
    }

    /**
     * Get modifiedat
     *
     * @return \DateTime
     */
    public function getModifiedat()
    {
        return $this->modifiedat;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Poll
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return Poll
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Poll
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Add opinions
     *
     * @param \nkgPollBundle\Entity\Opinion $opinions
     * @return Poll
     */
    public function addOpinion(\nkgPollBundle\Entity\Opinion $opinions)
    {
        $this->opinions[] = $opinions;

        return $this;
    }

    /**
     * Remove opinions
     *
     * @param \nkgPollBundle\Entity\Opinion $opinions
     */
    public function removeOpinion(\nkgPollBundle\Entity\Opinion $opinions)
    {
        $this->opinions->removeElement($opinions);
    }

    /**
     * Get opinions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpinions()
    {
        return $this->opinions;
    }
}
