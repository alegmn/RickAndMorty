<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validation;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Character $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Character $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(String $name, String $lastName, int $age, bool $isProtagonist, String $occupation, String $gender, bool $flush = true)
    {
        $newCharacter = new Character();
        $newCharacter
            ->setName($name)
            ->setLastName($lastName)
            ->setAge($age)
            ->setIsProtagonist($isProtagonist)
            ->setOccupation($occupation)
            ->setGender($gender);

        $this->_em->persist($newCharacter);
        if ($flush) {
            $this->_em->flush();
        }
        return $newCharacter;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Character $character, array $array_data, bool $flush = true)
    {
        foreach($array_data as $key => $data) {
             $method = "set".ucwords($key);
             if (method_exists(Character::class, $method)) {
                 $character->$method($data);
             }
        }

        $this->_em->persist($character);
        if ($flush) {
            $this->_em->flush();
        }
        return $character;
    }

    public function findAllPagination(String $name, String $gender, $page, $limit): array
    {
        // automatically knows to select Products
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('c')
            //->where('p.gender = :gender')
            //->setParameter('gender', $gender)
            ->orderBy('c.id', 'ASC');

        if (!empty($gender)) {
            $qb->where('c.gender = :gender')
            ->setParameter('gender', $gender);
        }
        if (!empty($name)) {
            $qb->where('c.name = :name')
            ->setParameter('name', $name);
        }

        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);
        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($page-1))
            ->setMaxResults($limit);

        return array("paginator" => $paginator, "totalItems" => $totalItems, "totalPages"=> $totalPages);
        //return $query->execute();
    }

    // /**
    //  * @return Character[] Returns an array of Character objects
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
    public function findOneBySomeField($value): ?Character
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
