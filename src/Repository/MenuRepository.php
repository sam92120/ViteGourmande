<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.theme', 't')
            ->addSelect('t')
            ->leftJoin('m.plats', 'p')
            ->addSelect('p');

        if (!empty($filters['regime'])) {
            $qb->andWhere('m.regime = :regime')
                ->setParameter('regime', $filters['regime']);
        }

        if (!empty($filters['theme'])) {
            $qb->andWhere('t.id = :theme')
                ->setParameter('theme', $filters['theme']);
        }

        if (!empty($filters['type'])) {
            $qb->andWhere('LOWER(p.type) LIKE :type')
                ->setParameter('type', '%' . strtolower(trim($filters['type'])) . '%');
        }

        if (!empty($filters['prix']) && in_array($filters['prix'], ['asc', 'desc'], true)) {
            $qb->orderBy('m.prix_par_pers', strtoupper($filters['prix']));
        } else {
            $qb->orderBy('m.prix_par_pers', 'ASC');
        }

        return $qb->getQuery()->getResult();
    }
}
    //    /**
    //     * @return Menu[] Returns an array of Menu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Menu
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

