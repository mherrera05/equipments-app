<?php

namespace Rto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models
 *
 * @ORM\Table(name="models", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_models_brands_idx", columns={"brands_id"}), @ORM\Index(name="fk_models_type_equipments1_idx", columns={"type_equipments_id"})})
 * @ORM\Entity
 */
class Models
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
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \Brands
     *
     * @ORM\ManyToOne(targetEntity="Brands")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brands_id", referencedColumnName="id")
     * })
     */
    private $brands;

    /**
     * @var \TypeEquipments
     *
     * @ORM\ManyToOne(targetEntity="TypeEquipments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_equipments_id", referencedColumnName="id")
     * })
     */
    private $typeEquipments;



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
     * @return Models
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
     * Set description
     *
     * @param string $description
     * @return Models
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
     * Set brands
     *
     * @param \Rto\CoreBundle\Entity\Brands $brands
     * @return Models
     */
    public function setBrands(\Rto\CoreBundle\Entity\Brands $brands = null)
    {
        $this->brands = $brands;

        return $this;
    }

    /**
     * Get brands
     *
     * @return \Rto\CoreBundle\Entity\Brands 
     */
    public function getBrands()
    {
        return $this->brands;
    }

    /**
     * Set typeEquipments
     *
     * @param \Rto\CoreBundle\Entity\TypeEquipments $typeEquipments
     * @return Models
     */
    public function setTypeEquipments(\Rto\CoreBundle\Entity\TypeEquipments $typeEquipments = null)
    {
        $this->typeEquipments = $typeEquipments;

        return $this;
    }

    /**
     * Get typeEquipments
     *
     * @return \Rto\CoreBundle\Entity\TypeEquipments 
     */
    public function getTypeEquipments()
    {
        return $this->typeEquipments;
    }
}
