<?php

namespace Sidus\EAVDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\Value as BaseValue;

/**
 * Value
 *
 * @ORM\Table(name="sidus_value")
 * @ORM\Entity(repositoryClass="Sidus\EAVDemoBundle\Entity\ValueRepository")
 */
class Value extends BaseValue
{
    /**
     * @var Data
     * @ORM\ManyToOne(targetEntity="Sidus\EAVDemoBundle\Entity\Data", inversedBy="values")
     * @ORM\JoinColumn(name="data_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $data;

    /**
     * @var Data
     * @ORM\ManyToOne(targetEntity="Sidus\EAVDemoBundle\Entity\Data")
     * @ORM\JoinColumn(name="data_value_id", referencedColumnName="id", onDelete="cascade", nullable=true)
     */
    protected $dataValue;
}