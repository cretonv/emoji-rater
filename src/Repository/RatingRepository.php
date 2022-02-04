<?php

namespace App\Repository;

use App\Context\WebsiteContext;
use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry        $registry,
        private WebsiteContext $websiteContext,
    ) {
        parent::__construct($registry, Rating::class);
    }

    // /**
    //  * @return Rating[] Returns an array of Rating objects
    //  */
    private function getByWebsiteQuery(): \Doctrine\ORM\QueryBuilder {
        $website = $this->websiteContext->getWebsite();
        return $this->createQueryBuilder('r')
            ->leftJoin('r.product', 'p')
            ->andWhere('p.website = :val')
            ->setParameter('val', $website);
    }

    public function findAllWebsite() {
        return $this->getByWebsiteQuery()
            ->getQuery()
            ->getResult();
    }

    public function findByUserEmail($email) {
        return $this->getByWebsiteQuery()
            ->andWhere('r.authorUserEmail = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Rating
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
