<?php

namespace Rto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentsUninstallation
 *
 * @ORM\Table(name="equipments_uninstallation", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_equipments_uninstallation_equipments1_idx", columns={"equipments_id"}), @ORM\Index(name="fk_equipments_uninstallation_uninstallations1_idx", columns={"uninstallations_id"})})
 * @ORM\Entity
 */
class EquipmentsUninstallation
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
     * @var \Uninstallations
     *
     * @ORM\ManyToOne(targetEntity="Uninstallations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uninstallations_id", referencedColumnName="id")
     * })
     */
    private $uninstallations;



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
     * @return EquipmentsUninstallation
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
     * Set uninstallations
     *
     * @param \Rto\CoreBundle\Entity\Uninstallations $uninstallations
     * @return EquipmentsUninstallation
     */
    public function setUninstallations(\Rto\CoreBundle\Entity\Uninstallations $uninstallations = null)
    {
        $this->uninstallations = $uninstallations;

        return $this;
    }

    /**
     * Get uninstallations
     *
     * @return \Rto\CoreBundle\Entity\Uninstallations 
     */
    public function getUninstallations()
    {
        return $this->uninstallations;
    }
}
