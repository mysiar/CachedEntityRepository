<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CachedEntityRepository extends EntityRepository
{
    /**
     * @var int
     */
    protected $lifetime = null;

    /**
     * @var string
     */
    protected $alias = null;

    public function findAll()
    {
        $qb = $this->createQueryBuilder('e');
        $query = $qb->getQuery();
        $query->useResultCache(true, $this->lifetime, $this->alias);

        return $query->getArrayResult();
    }

    public function find($id)
    {
        $identifier = $this->getClassMetadata()->getIdentifierFieldNames()[0];
        $criteria = array( $identifier => $id);

        return $this->createQuery($criteria)->getOneOrNullResult();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->createQuery($criteria, $orderBy, $limit, $offset)->getArrayResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->createQuery($criteria, $orderBy)->getOneOrNullResult();
    }

    private function createQuery(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('e');

        foreach ($criteria as $key => $value) {
            $qb->andWhere("e.$key = :$key");
            $qb->setParameter($key, $value);
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->addOrderBy("e.$key = :$key");
                $qb->setParameter($key, $value);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }

        $query = $qb->getQuery();
        $query->useResultCache(true, $this->lifetime, $this->alias);

        return $query;
    }
}
