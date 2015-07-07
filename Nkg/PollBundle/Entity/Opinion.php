<?php

namespace Nkg\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Opinion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Nkg\PollBundle\Entity\OpinionRepository")
 */
class Opinion
{
    /**
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="opinion")
     * @ORM\JoinColumn(name="poll_id", referencedColumnName="id")
     */
    protected $poll;


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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="votes", nullable=true, type="integer")
     */
    private $votes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastvoteat", nullable=true, type="datetime")
     */
    private $lastvoteat;


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
     * @return Opinion
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
     * @return Opinion
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
     * Set votes
     *
     * @param integer $votes
     * @return Opinion
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Add 1 vote
     *
     * @param integer $votes
     * @return Opinion
     */
    public function addVote()
    {
        $this->votes += 1;

        return $this;
    }

    /**
     * Get votes
     *
     * @return integer
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set lastvoteat
     *
     * @param \DateTime $lastvoteat
     * @return Opinion
     */
    public function setLastvoteat($lastvoteat)
    {
        $this->lastvoteat = $lastvoteat;

        return $this;
    }

    /**
     * Get lastvoteat
     *
     * @return \DateTime
     */
    public function getLastvoteat()
    {
        return $this->lastvoteat;
    }

    /**
     * Set poll
     *
     * @param \Nkg\PollBundle\Entity\Poll $poll
     * @return Opinion
     */
    public function setPoll(\Nkg\PollBundle\Entity\Poll $poll = null)
    {
        $this->poll = $poll;

        return $this;
    }

    /**
     * Get poll
     *
     * @return \Nkg\PollBundle\Entity\Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }
}
