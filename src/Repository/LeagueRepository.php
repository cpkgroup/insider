<?php

namespace App\Repository;

use App\Entity\League;
use Doctrine\ORM\EntityRepository;

class LeagueRepository extends EntityRepository
{

    /**
     * @param int $leagueName
     * @return League
     * @throws \Doctrine\ORM\ORMException
     */
    public function createLeague($leagueName)
    {
        $league = new League();
        $league->setName($leagueName);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($league);

        return $league;
    }

    /**
     * @param int $leagueId
     * @return array
     */
    public function findAllMatches($leagueId)
    {
        return $this->findBy(['league' => $leagueId]);
    }
}