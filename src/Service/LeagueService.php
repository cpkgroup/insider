<?php

namespace App\Service;

use App\Entity\League;
use App\Entity\Match;
use Doctrine\ORM\EntityManagerInterface;

class LeagueService
{

    protected $entityManager;
    protected $matchService;

    public function __construct(EntityManagerInterface $entityManager, MatchService $matchService)
    {
        $this->entityManager = $entityManager;
        $this->matchService = $matchService;
    }

    /**
     * @param $leagueName
     * @param $teamIDs
     */
    public function createLeague($leagueName, $teamIDs)
    {
        $leagueRepository = $this->entityManager->getRepository(League::class);
        $matchRepository = $this->entityManager->getRepository(Match::class);
        $league = $leagueRepository->createLeague($leagueName);

        $weeks = count($teamIDs) - 1;
        for ($week = 0; $week < $weeks; $week++) {
            $arrangeGames = $this->arrangeGames($teamIDs, $week);
            $hosts = $arrangeGames[0];
            $guests = $arrangeGames[1];
            for ($j = 0; $j < count($hosts); $j++) {
                // home match
                $matchRepository->createMatch($league, $hosts[$j], $guests[$j], $week + 1);
                // away match
                $matchRepository->createMatch($league, $guests[$j], $hosts[$j], $weeks + $week + 1);
            }
        }
        $this->entityManager->flush();
    }

    /**
     * @param $leagueId
     * @param $week
     */
    public function playWeek($leagueId, $week)
    {
        $matchRepository = $this->entityManager->getRepository(Match::class);
        $matches = $matchRepository->findWeekMatches($leagueId, $week);

        /** @var Match $match */
        foreach ($matches as $match) {
            if (!$match->getPlayedAt()) {
                $this->matchService->playGame($match);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param $leagueId
     */
    public function playAll($leagueId)
    {
        $leagueRepository = $this->entityManager->getRepository(Match::class);
        $matches = $leagueRepository->findAllMatches($leagueId);

        /** @var Match $match */
        foreach ($matches as $match) {
            if (!$match->getPlayedAt()) {
                $this->matchService->playGame($match);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param $teams
     * @param $week
     * @return array
     */
    protected function arrangeGames($teams, $week)
    {
        $first = array_shift($teams);
        for ($i = 0; $i < $week; $i++) {
            array_unshift($teams, array_pop($teams));
        }
        array_unshift($teams, $first);
        $half = count($teams) / 2;
        return [
            array_slice($teams, 0, $half),
            array_reverse(array_slice($teams, $half))
        ];
    }
}