<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllWithMoreThan5Posts()
    {
        return $this->getFindAllWithMoreThan5PostQuery()
            ->getQuery()
            ->getResult();
    }

    public function findAllWithMoreThan5PostsExceptUser(User $user)
    {
        return $this->getFindAllWithMoreThan5PostQuery()
            ->andHaving('u != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getFindAllWithMoreThan5PostQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->select('u')
            ->innerJoin('u.posts', 'mp')
            ->groupBy('u')
            ->having('count(mp) > 5');
    }
}
