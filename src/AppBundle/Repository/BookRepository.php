<?php

namespace AppBundle\Repository;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends CachedEntityRepository
{
    protected $lifetime = 3600;
    protected $alias = '_books_';

}