<?php

namespace FeedBundle\Repository;

use FeedBundle\Repository\Interfaces\RepositoryInterface;

/**
 * NewsEntityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsEntityRepository extends \Doctrine\ORM\EntityRepository implements RepositoryInterface
{
    public function truncate()
    {
        return $this->getEntityManager()
            ->createQuery('DELETE FROM FeedBundle:NewsEntity')->execute();
    }
}
