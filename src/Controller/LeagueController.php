<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Match;
use App\Entity\Team;
use App\Service\LeagueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LeagueController extends AbstractController
{
    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createTeamAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? '';
        $strength = $data['strength'] ?? 1;

        $team = new Team();
        $team->setName($name);
        $team->setStrength($strength);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($team);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     *
     * @param LeagueService $leagueService
     * @param Request $request
     * @return JsonResponse
     */
    public function createLeagueAction(LeagueService $leagueService, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? '';
        $teams = $data['teams'] ?? [];
        $teams = array_filter($teams);
        $teams = array_slice($teams, 0, 4);

        // @TODO validation for teams
        $leagueService->createLeague($name, $teams);

        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function showLeaguesAction()
    {
        $leagueRepository = $this->getDoctrine()->getRepository(League::class);

        $leagues = $leagueRepository->findAll();
        $result = [];
        /** @var League $league */
        foreach ($leagues as $league) {
            $result[] = [
                'id' => $league->getId(),
                'name' => $league->getName()
            ];
        }

        return new JsonResponse([
            'leagues' => $result,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function showTeamsAction()
    {
        $teamRepository = $this->getDoctrine()->getRepository(Team::class);

        $teams = $teamRepository->findAll();
        $result = [];
        /** @var Team $team */
        foreach ($teams as $team) {
            $result[] = [
                'id' => $team->getId(),
                'name' => $team->getName()
            ];
        }

        return new JsonResponse([
            'teams' => $result,
        ], JsonResponse::HTTP_OK);
    }

    /**
     *
     * @param LeagueService $leagueService
     * @param Request $request
     * @return JsonResponse
     */
    public function showWeekAction(LeagueService $leagueService, Request $request)
    {
        $week = (int)$request->get('week', 1);
        $leagueId = (int)$request->get('leagueId');

        $table = $leagueService->getTable($leagueId, $week);

        // sort the table
        $table = $leagueService->sortLeagueTable($table);

        // calculate chance
        $table = $leagueService->calculateChance($table, $week);

        $matches = [];
        if ($week) {
            $matches = $leagueService->getMatches($leagueId, $week);
        }

        return new JsonResponse([
            'matches' => $matches,
            'table' => $table,
            'weeks' => (count($table) - 1) * 2,
            'status' => 'succeed',
        ], JsonResponse::HTTP_OK);
    }

    /**
     *
     * @param LeagueService $leagueService
     * @param Request $request
     * @return JsonResponse
     */
    public function playMatchAction(LeagueService $leagueService, Request $request)
    {
        $week = (int)$request->get('week', 1);
        $leagueId = (int)$request->get('leagueId');

        $leagueService->playWeek($leagueId, $week);

        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_OK);
    }

    /**
     *
     * @param LeagueService $leagueService
     * @param Request $request
     * @return JsonResponse
     */
    public function playAllMatchAction(LeagueService $leagueService, Request $request)
    {
        $leagueId = (int)$request->get('leagueId');

        $leagueService->playAll($leagueId);

        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateMatchAction(Request $request)
    {
        $matchId = (int)$request->get('matchId');
        $teamHostGoal = (int)$request->get('teamHostGoal');
        $teamGuestGoal = (int)$request->get('teamGuestGoal');

        $matchRepository = $this->getDoctrine()->getRepository(Match::class);
        $matchRepository->updateMatch($matchId, $teamHostGoal, $teamGuestGoal);

        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_OK);
    }
}
