<?php

namespace Rto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Worktime
 *
 * @ORM\Table(name="worktime", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_worktime_locations1_idx", columns={"locations_id"}), @ORM\Index(name="fk_worktime_users1_idx", columns={"users_id"})})
 * @ORM\Entity
 */
class Worktime
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Worktime
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Worktime
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set locations
     *
     * @param \Rto\CoreBundle\Entity\Locations $locations
     * @return Worktime
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
     * Set users
     *
     * @param \Rto\CoreBundle\Entity\Users $users
     * @return Worktime
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
