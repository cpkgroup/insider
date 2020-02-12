<?php

namespace App\Service;

use App\Entity\League;
use App\Entity\Match;
use App\Service\Compare\GeneralComparator;
use App\Service\Compare\GoalDiffComparator;
use App\Service\Compare\ScoreComparator;
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
        $matchRepository = $this->entityManager->getRepository(Match::class);
        $matches = $matchRepository->findAllMatches($leagueId);

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
     * @param $weekNumber
     * @return array
     */
    public function getTable($leagueId, $weekNumber = null)
    {
        $matchRepository = $this->entityManager->getRepository(Match::class);
        $matches = $matchRepository->findMatchesUntilWeek($leagueId, $weekNumber);
        $leagueTable = [];
        /** @var Match $match */
        foreach ($matches as $match) {
            $leagueTable[$match->getTeamHost()->getId()] = $this->updateLeagueRow(
                $leagueTable[$match->getTeamHost()->getId()] ?? [],
                $match->getGoalsHost(),
                $match->getGoalsGuest(),
                $match->getTeamHost()->getId(),
                $match->getTeamHost()->getName()
            );
            $leagueTable[$match->getTeamGuest()->getId()] = $this->updateLeagueRow(
                $leagueTable[$match->getTeamGuest()->getId()] ?? [],
                $match->getGoalsGuest(),
                $match->getGoalsHost(),
                $match->getTeamGuest()->getId(),
                $match->getTeamGuest()->getName()
            );
        }

        return array_values($leagueTable);
    }

    /**
     * @param $leagueId
     * @param $weekNumber
     * @return array
     */
    public function getMatches($leagueId, $weekNumber)
    {
        $matchRepository = $this->entityManager->getRepository(Match::class);
        $matches = $matchRepository->findWeekMatches($leagueId, $weekNumber);
        $resultTable = [];
        /** @var Match $match */
        foreach ($matches as $match) {
            $resultTable[] = [
                'host' => $match->getTeamHost()->getName(),
                'goalsHost' => $match->getGoalsHost(),
                'gust' => $match->getTeamGuest()->getName(),
                'goalsGuest' => $match->getGoalsGuest(),
                'playedAt' => $match->getPlayedAt() ? $match->getPlayedAt()->format('Y-m-d H:i') : null,
            ];
        }

        return $resultTable;
    }

    /**
     * @param $leagueTable
     * @return mixed
     */
    public function calculateChance($leagueTable, $week = null)
    {
        // @TODO upgrade this algorithm
        $teams = count($leagueTable);
        $weeks = ($teams - 1) * 2; // home and away
        $matchesPerWeek = $teams / 2;
        $matches = $weeks * $matchesPerWeek;

        $allPossiblePoints = $week ? ($matches - $week) * 3 : 0;
        $sumAchievedPoints = array_sum(array_column($leagueTable, 'PTS'));

        if (!$allPossiblePoints) {
            foreach ($leagueTable as $key => $item) {
                $leagueTable[$key]['chance'] = $key == 0 ? 100 : 0;
            }
            return $leagueTable;
        }

        foreach ($leagueTable as $key => $item) {
            $leagueTable[$key]['chance'] = round($item['PTS'] / ($allPossiblePoints + $sumAchievedPoints) * 100);
        }

        return $leagueTable;
    }

    /**
     * @param $team
     * @param $goalScored
     * @param $goalReceived
     * @param $teamId
     * @param $teamName
     * @return mixed
     */
    protected function updateLeagueRow($team, $goalScored, $goalReceived, $teamId, $teamName)
    {
        if (!isset($team['TeamId'])) {
            $team['TeamId'] = $teamId;
            $team['TeamName'] = $teamName;
            $team['PTS'] = $this->getPoint($goalScored, $goalReceived);
            $team['P'] = 1;
            $team['W'] = $this->isWinner($goalScored, $goalReceived) ? 1 : 0;
            $team['D'] = $this->isDraw($goalScored, $goalReceived) ? 1 : 0;
            $team['L'] = $this->isLooser($goalScored, $goalReceived) ? 1 : 0;
            $team['GD'] = $goalScored - $goalReceived;
            $team['GF'] = $goalScored;
            $team['GA'] = $goalReceived;
        } else {
            $team['PTS'] += $this->getPoint($goalScored, $goalReceived);
            $team['P'] += 1;
            $team['W'] += $this->isWinner($goalScored, $goalReceived) ? 1 : 0;
            $team['D'] += $this->isDraw($goalScored, $goalReceived) ? 1 : 0;
            $team['L'] += $this->isLooser($goalScored, $goalReceived) ? 1 : 0;
            $team['GD'] += $goalScored - $goalReceived;
            $team['GF'] += $goalScored;
            $team['GA'] += $goalReceived;
        }
        return $team;
    }

    /**
     * @param $leagueTable
     * @return mixed
     */
    public function sortLeagueTable($leagueTable)
    {
        uasort($leagueTable, [new GeneralComparator(), 'compare']);

        return array_values($leagueTable);
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

    /**
     * @param $goalScored
     * @param $goalReceived
     * @return int
     */
    protected function getPoint($goalScored, $goalReceived)
    {
        if ($goalScored == $goalReceived) {
            return 1;
        }
        if ($goalScored > $goalReceived) {
            return 3;
        }
        return 0;
    }

    /**
     * @param $goalScored
     * @param $goalReceived
     * @return int
     */
    protected function isWinner($goalScored, $goalReceived)
    {
        if ($goalScored > $goalReceived) {
            return true;
        }
        return false;
    }

    /**
     * @param $goalScored
     * @param $goalReceived
     * @return int
     */
    protected function isLooser($goalScored, $goalReceived)
    {
        if ($goalScored < $goalReceived) {
            return true;
        }
        return false;
    }

    /**
     * @param $goalScored
     * @param $goalReceived
     * @return int
     */
    protected function isDraw($goalScored, $goalReceived)
    {
        if ($goalScored == $goalReceived) {
            return true;
        }
        return false;
    }
}