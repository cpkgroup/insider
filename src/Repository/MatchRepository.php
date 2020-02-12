<?php

namespace App\Repository;

use App\Entity\League;
use App\Entity\Match;
use App\Entity\Team;
use Doctrine\ORM\EntityRepository;

class MatchRepository extends EntityRepository
{

    /**
     * @param int $leagueId
     * @param int $week
     * @return array
     */
    public function findWeekMatches($leagueId, $week)
    {
        return $this->findBy(['league' => $leagueId, 'weekNumber' => $week]);
    }

    /**
     * @param int $leagueId
     * @return array
     */
    public function findAllMatches($leagueId)
    {
        return $this->findBy(['league' => $leagueId]);
    }

    /**
     * @param League $league
     * @param $hostId
     * @param $guestId
     * @param $week
     * @return Match
     * @throws \Doctrine\ORM\ORMException
     */
    public function createMatch(League $league, $hostId, $guestId, $week)
    {
        $match = new Match();

        $entityManager = $this->getEntityManager();
        $reamRepository = $entityManager->getRepository(Team::class);

        $match->setTeamHost($reamRepository->find($hostId));
        $match->setTeamGuest($reamRepository->find($guestId));
        $match->setWeekNumber($week);
        $match->setLeague($league);
        $entityManager->persist($match);
        return $match;
    }

    /**
     * @param $matchId
     * @param $teamHostGoal
     * @param $teamGuestGoal
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateMatch($matchId, $teamHostGoal, $teamGuestGoal)
    {
        $match = $this->find($matchId);
        $match->setTeamHost($teamHostGoal);
        $match->setTeamGuest($teamGuestGoal);
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    /**
     * @param $leagueId
     * @param $weekNumber
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findMatchesUntilWeek($leagueId, $weekNumber = null)
    {
        $builder = $this->createQueryBuilder('m')
            ->where('m.league = :league')->setParameter('league', $leagueId);

        if ($weekNumber) {
            $builder->andWhere('m.weekNumber <= :weekNumber')->setParameter('weekNumber', $weekNumber);
        }

        return $builder->getQuery()
            ->getResult();
    }
}