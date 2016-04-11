<?php

namespace Demo\EAVModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sidus\EAVModelBundle\Entity\Value as BaseValue;
use Demo\AssetBundle\Entity\Document;
use Demo\AssetBundle\Entity\Image;

/**
 * Value
 *
 * @ORM\Table(name="demo_value")
 * @ORM\Entity(repositoryClass="Demo\EAVModelBundle\Entity\ValueRepository")
 */
class Value extends BaseValue
{
    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="Demo\AssetBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(name="image_value_id", referencedColumnName="id", onDelete="cascade", nullable=true)
     */
    protected $imageValue;

    /**
     * @var Document
     * @ORM\ManyToOne(targetEntity="Demo\AssetBundle\Entity\Document", cascade={"persist"})
     * @ORM\JoinColumn(name="document_value_id", referencedColumnName="id", onDelete="cascade", nullable=true)
     */
    protected $documentValue;

    /**
     * Context Parameter
     * @var bool
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    protected $language;

    /**
     * @return Image
     */
    public function getImageValue()
    {
        return $this->imageValue;
    }

    /**
     * @param Image $imageValue
     */
    public function setImageValue(Image $imageValue = null)
    {
        $this->imageValue = $imageValue;
    }

    /**
     * @return Document
     */
    public function getDocumentValue()
    {
        return $this->documentValue;
    }

    /**
     * @param Document $documentValue
     */
    public function setDocumentValue(Document $documentValue = null)
    {
        $this->documentValue = $documentValue;
    }

    /**
     * @return array
     */
    public function getContextKeys()
    {
        return ['language'];
    }
}
