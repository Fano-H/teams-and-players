<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function getPaginatedTeams($page = 1, $postsPerPage = 1)
    {
        $query = $this->createQueryBuilder('tm')
            ->orderBy('tm.id', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query);

        $paginator->getQuery()
            ->setFirstResult($postsPerPage * ($page - 1))
            ->setMaxResults($postsPerPage);

        return $paginator;
    }
}
