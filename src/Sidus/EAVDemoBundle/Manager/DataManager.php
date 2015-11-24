<?php

namespace Sidus\EAVDemoBundle\Manager;

use LAG\AdminBundle\Manager\GenericManager;

class DataManager extends GenericManager
{
    /**
     * Override LAM's Admin bundle manager to make flush global
     *
     * @param $entity
     * @param bool|true $flush
     */
    public function save($entity, $flush = true)
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
