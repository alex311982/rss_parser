<?php

namespace Gubarev\Bundle\FeedBundle\Repository\Traits;

use Doctrine\ORM\Persisters\Entity\EntityPersister;

trait Countable
{
    public function findTotalByConditions(array $criteria = []): int
    {
        /** @var EntityPersister $persister */
        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

        return $persister->count($criteria);
    }
}
