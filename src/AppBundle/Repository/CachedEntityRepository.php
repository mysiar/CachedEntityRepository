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

        $qb = $this->createQueryBuilder('e')
            ->where("e.$identifier = :param")
            ->setParameter('param', $id);

        $query = $qb->getQuery();
        $query->useResultCache(true, $this->lifetime, $this->alias);

        return $query->getOneOrNullResult();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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

        return $query->getArrayResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
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

        $query = $qb->getQuery();
        $query->useResultCache(true, $this->lifetime, $this->alias);

        return $query->getOneOrNullResult();
    }
}
