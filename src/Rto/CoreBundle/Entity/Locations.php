<?php

namespace Rto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Locations
 *
 * @ORM\Table(name="locations", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_locations_projects1_idx", columns={"projects_id"}), @ORM\Index(name="fk_locations_type_locations1_idx", columns={"type_locations_id"})})
 * @ORM\Entity
 */
class Locations
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="length", type="float", precision=10, scale=0, nullable=true)
     */
    private $length;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gather", type="boolean", nullable=false)
     */
    private $gather;

    /**
     * @var boolean
     *
     * @ORM\Column(name="workplace", type="boolean", nullable=false)
     */
    private $workplace;

    /**
     * @var \Projects
     *
     * @ORM\ManyToOne(targetEntity="Projects")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="projects_id", referencedColumnName="id")
     * })
     */
    private $projects;

    /**
     * @var \TypeLocations
     *
     * @ORM\ManyToOne(targetEntity="TypeLocations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_locations_id", referencedColumnName="id")
     * })
     */
    private $typeLocations;



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
     * @return Locations
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
     * Set latitude
     *
     * @param float $latitude
     * @return Locations
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set length
     *
     * @param float $length
     * @return Locations
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return float 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set gather
     *
     * @param boolean $gather
     * @return Locations
     */
    public function setGather($gather)
    {
        $this->gather = $gather;

        return $this;
    }

    /**
     * Get gather
     *
     * @return boolean 
     */
    public function getGather()
    {
        return $this->gather;
    }

    /**
     * Set workplace
     *
     * @param boolean $workplace
     * @return Locations
     */
    public function setWorkplace($workplace)
    {
        $this->workplace = $workplace;

        return $this;
    }

    /**
     * Get workplace
     *
     * @return boolean 
     */
    public function getWorkplace()
    {
        return $this->workplace;
    }

    /**
     * Set projects
     *
     * @param \Rto\CoreBundle\Entity\Projects $projects
     * @return Locations
     */
    public function setProjects(\Rto\CoreBundle\Entity\Projects $projects = null)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Get projects
     *
     * @return \Rto\CoreBundle\Entity\Projects 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set typeLocations
     *
     * @param \Rto\CoreBundle\Entity\TypeLocations $typeLocations
     * @return Locations
     */
    public function setTypeLocations(\Rto\CoreBundle\Entity\TypeLocations $typeLocations = null)
    {
        $this->typeLocations = $typeLocations;

        return $this;
    }

    /**
     * Get typeLocations
     *
     * @return \Rto\CoreBundle\Entity\TypeLocations 
     */
    public function getTypeLocations()
    {
        return $this->typeLocations;
    }
}
