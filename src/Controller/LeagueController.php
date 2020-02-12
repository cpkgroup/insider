<?php

namespace App\Controller;

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
        $name = $request->get('name');
        $strength = (int)$request->get('strength');

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
        $name = $request->get('name');
        $teams = $request->get('teams');

        // @TODO validation for teams
        $leagueService->createLeague($name, $teams);

        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     *
     * @return JsonResponse
     */
    public function showWeekAction(LeagueService $leagueService, Request $request)
    {
        $week = (int)$request->get('week');
        $leagueId = (int)$request->get('leagueId');

        $table = $leagueService->getTable($leagueId, $week);
        // sort the table
        $table = $leagueService->sortLeagueTable($table);

        return new JsonResponse([
            'data' => $table,
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

        $table = $leagueService->getTable($leagueId, $week);
        // sort the table
        $table = $leagueService->sortLeagueTable($table);

        return new JsonResponse([
            'data' => $table,
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

        $table = $leagueService->getTable($leagueId);
        // sort the table
        $table = $leagueService->sortLeagueTable($table);

        return new JsonResponse([
            'data' => $table,
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
