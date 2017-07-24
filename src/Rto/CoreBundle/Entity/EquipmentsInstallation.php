<?php

namespace Rto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentsInstallation
 *
 * @ORM\Table(name="equipments_installation", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_equipments_installation_equipments1_idx", columns={"equipments_id"}), @ORM\Index(name="fk_equipments_installation_installation1_idx", columns={"installation_id"})})
 * @ORM\Entity
 */
class EquipmentsInstallation
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
     * @var \Equipments
     *
     * @ORM\ManyToOne(targetEntity="Equipments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipments_id", referencedColumnName="id")
     * })
     */
    private $equipments;

    /**
     * @var \Installations
     *
     * @ORM\ManyToOne(targetEntity="Installations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="installation_id", referencedColumnName="id")
     * })
     */
    private $installation;



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
     * Set equipments
     *
     * @param \Rto\CoreBundle\Entity\Equipments $equipments
     * @return EquipmentsInstallation
     */
    public function setEquipments(\Rto\CoreBundle\Entity\Equipments $equipments = null)
    {
        $this->equipments = $equipments;

        return $this;
    }

    /**
     * Get equipments
     *
     * @return \Rto\CoreBundle\Entity\Equipments 
     */
    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * Set installation
     *
     * @param \Rto\CoreBundle\Entity\Installations $installation
     * @return EquipmentsInstallation
     */
    public function setInstallation(\Rto\CoreBundle\Entity\Installations $installation = null)
    {
        $this->installation = $installation;

        return $this;
    }

    /**
     * Get installation
     *
     * @return \Rto\CoreBundle\Entity\Installations 
     */
    public function getInstallation()
    {
        return $this->installation;
    }
}
