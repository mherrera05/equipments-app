<?php

namespace Rto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipments
 *
 * @ORM\Table(name="equipments", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="serail_UNIQUE", columns={"serial"}), @ORM\UniqueConstraint(name="hostname_UNIQUE", columns={"hostname", "serial", "mac"})}, indexes={@ORM\Index(name="fk_equipments_models1_idx", columns={"models_id"}), @ORM\Index(name="fk_equipments_locations1_idx", columns={"locations_id"}), @ORM\Index(name="fk_equipments_users1_idx", columns={"users_id"})})
 * @ORM\Entity
 */
class Equipments
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="serial", type="string", length=60, nullable=false)
     */
    private $serial;

    /**
     * @var string
     *
     * @ORM\Column(name="mac", type="string", length=60, nullable=true)
     */
    private $mac;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean", nullable=false)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=60, nullable=true)
     */
    private $hostname;

    /**
     * @var \Locations
     *
     * @ORM\ManyToOne(targetEntity="Locations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="locations_id", referencedColumnName="id")
     * })
     */
    private $locations;

    /**
     * @var \Models
     *
     * @ORM\ManyToOne(targetEntity="Models")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="models_id", referencedColumnName="id")
     * })
     */
    private $models;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     * })
     */
    private $users;



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
     * Set serial
     *
     * @param string $serial
     * @return Equipments
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial
     *
     * @return string 
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set mac
     *
     * @param string $mac
     * @return Equipments
     */
    public function setMac($mac)
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * Get mac
     *
     * @return string 
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Equipments
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return Equipments
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Equipments
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Equipments
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set locations
     *
     * @param \Rto\CoreBundle\Entity\Locations $locations
     * @return Equipments
     */
    public function setLocations(\Rto\CoreBundle\Entity\Locations $locations = null)
    {
        $this->locations = $locations;

        return $this;
    }

    /**
     * Get locations
     *
     * @return \Rto\CoreBundle\Entity\Locations 
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Set models
     *
     * @param \Rto\CoreBundle\Entity\Models $models
     * @return Equipments
     */
    public function setModels(\Rto\CoreBundle\Entity\Models $models = null)
    {
        $this->models = $models;

        return $this;
    }

    /**
     * Get models
     *
     * @return \Rto\CoreBundle\Entity\Models 
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Set users
     *
     * @param \Rto\CoreBundle\Entity\Users $users
     * @return Equipments
     */
    public function setUsers(\Rto\CoreBundle\Entity\Users $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \Rto\CoreBundle\Entity\Users 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
