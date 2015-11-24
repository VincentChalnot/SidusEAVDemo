<?php

namespace Sidus\EAVDemoBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\Data as BaseData;

/**
 * Data
 *
 * @ORM\Table(name="sidus_data")
 * @ORM\Entity(repositoryClass="Sidus\EAVDemoBundle\Entity\DataRepository")
 */
class Data extends BaseData
{
    /**
     * @var Data
     * @ORM\ManyToOne(targetEntity="Sidus\EAVDemoBundle\Entity\Data", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $parent;

    /**
     * @var Data[]
     * @ORM\OneToMany(targetEntity="Sidus\EAVDemoBundle\Entity\Data", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $children;

    /**
     * @var Value[]|Collection
     * @ORM\OneToMany(targetEntity="Sidus\EAVDemoBundle\Entity\Value", mappedBy="data", cascade={"persist", "remove"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $values;

    /**
     * @var boolean
     * @ORM\Column(name="is_removed", type="boolean")
     */
    protected $isRemoved = false;

    /**
     * @var boolean
     * @ORM\Column(name="is_archived", type="boolean")
     */
    protected $isArchived = false;

    /**
     * @return boolean
     */
    public function isIsArchived()
    {
        return $this->isArchived;
    }

    /**
     * @param boolean $isArchived
     * @return Data
     */
    public function setIsArchived($isArchived)
    {
        $this->isArchived = $isArchived;
        return $this;
    }

    /**
     * @param boolean $isRemoved
     * @return Data
     */
    public function setIsRemoved($isRemoved)
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRemoved()
    {
        return $this->isRemoved;
    }
}
