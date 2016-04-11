<?php

namespace Demo\EAVModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\Data as BaseData;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="demo_data")
 * @ORM\Entity(repositoryClass="Demo\EAVModelBundle\Entity\DataRepository")
 */
class Data extends BaseData
{
}
