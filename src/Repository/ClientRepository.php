<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Client::class);
        $this->em = $em;
    }

    public function findAllByAdresse($adresse) {
        $query = $this
        ->createQueryBuilder('c')
        ->select("c", "c.nom as nom_client", "c.adresse", "g.nom")
        ->join("c.groupe", "g")
        ->where("c.adresse like :adresse")
        ->setParameter("adresse", "%" . $adresse . "%");

        // dd($query->getQuery());

        return $query->getQuery()->getResult();
    }

    public function testDQL() {
        $query = $this->createQuery('
            SELECT c
            FROM App\Entity\Client c
            WHERE c.adresse like :adresse
        ')
        ->setParameter("adresse", "%a%");

        return $query->getResult();
    }

    public function testSQL() {
        $rsm = new ResultSetMapping();

        $query = $this->em->createNativeQuery('
            SELECT id, nom, adresse 
            FROM client 
            WHERE adresse = ?
        ', $rsm);

        $query->setParameter(1, 'amiens');

        $users = $query->getResult();
    }


    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
