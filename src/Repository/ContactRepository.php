<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    //public function findOneBySomeField($value): ?Contact
    //{
    //    return $this->createQueryBuilder('c')
    //        ->andWhere('c.exampleField = :val')
    //        ->setParameter('val', $value)
    //        ->getQuery()
    //        ->getOneOrNullResult()
    //    ;
    //

    /*
        @return Contact[]
    */

    public function searchAndPaginate(int $page, int $limit, ?string $status = '', ?string $search = ''): array
    {
        $offset = ($page - 1) * $limit;
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if ($status && $status !== 'all') {
            $qb->andWhere('c.status = :status')
                ->setParameter('status', $status);
        }

        $search = trim((string) $search);
        if ($search !== '') {
            $term = mb_strtolower($search);
            $qb->andWhere('LOWER(c.firstName) LIKE :s OR LOWER(c.name) LIKE :s')
                ->setParameter('s', '%' . $term . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
