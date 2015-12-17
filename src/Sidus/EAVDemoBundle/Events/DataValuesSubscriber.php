<?php

namespace Sidus\EAVDemoBundle\Events;

use Doctrine\Common\Collections\ArrayCollection;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sidus\EAVModelBundle\Entity\Data;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DataValuesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            TransformEvent::POST_TRANSFORM => 'addCustomProperty',
        ];
    }

    public function addCustomProperty(TransformEvent $event)
    {
        /** @var Document $document */
        $document = $event->getDocument();
        $data = $event->getObject();
        if (!$data instanceof Data) {
            return;
        }
        foreach ($data->getFamily()->getAttributes() as $attribute) {
            if ($attribute->isMultiple()) {
                $values = $data->getValuesData($attribute);
                if ($values instanceof ArrayCollection) {
                    $values = $values->toArray();
                }
                $document->set($attribute->getCode(), $values);
            } else {
                $document->set($attribute->getCode(), $data->getValueData($attribute));
            }
        }
    }
}
