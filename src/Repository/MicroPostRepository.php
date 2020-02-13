<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\QueryBuilder;

/**
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function findAllByUser(PersistentCollection $user){
        $qb = $this->createQueryBuilder('p');

        return $qb->select('p')
            ->where('p.user IN (:following)')
            ->setParameter('following', $user)
            ->orderBy('p.time', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
