<?php

namespace Gubarev\Bundle\FeedBundle\Repository;

use Gubarev\Bundle\FeedBundle\Repository\Interfaces\RepositoryInterface;
use Gubarev\Bundle\FeedBundle\Repository\Traits\Countable;

/**
 * CategoryEntityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryEntityRepository extends \Doctrine\ORM\EntityRepository implements RepositoryInterface
{
    use Countable;

    public function truncate()
    {
        return $this->getEntityManager()
            ->createQuery('DELETE FROM Gubarev\Bundle\FeedBundle\Entity\CategoryEntity')->execute();
    }
}
