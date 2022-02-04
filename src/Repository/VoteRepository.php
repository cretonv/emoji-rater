<?php

namespace App\Repository;

use App\Context\WebsiteContext;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private WebsiteContext $websiteContext) {
        parent::__construct($registry, Vote::class);
    }


    private function getByWebsiteQuery(): \Doctrine\ORM\QueryBuilder {
        $website = $this->websiteContext->getWebsite();

        return $this->createQueryBuilder('v')
            ->leftJoin('v.rating', 'r')
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
            ->andWhere('v.authorUserEmail = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Vote
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
