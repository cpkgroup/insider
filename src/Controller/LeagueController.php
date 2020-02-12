<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class LeagueController extends AbstractController
{
    /**
     *
     * @return JsonResponse
     */
    public function createTeamAction(Request $request) {
        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_ACCEPTED);
    }


    /**
     *
     * @return JsonResponse
     */
    public function createLeagueAction(Request $request) {
        return new JsonResponse([
            'status' => 'succeed',
        ], JsonResponse::HTTP_ACCEPTED);
    }

    /**
     *
     * @return JsonResponse
     */
    public function showWeekAction(Request $request)
    {
        return new JsonResponse([]);
    }

    /**
     *
     * @return JsonResponse
     */
    public function playMatchAction(Request $request)
    {
        return new JsonResponse([]);
    }

    /**
     *
     * @return JsonResponse
     */
    public function playAllMatchAction(Request $request)
    {
        return new JsonResponse([]);
    }

    /**
     *
     * @return JsonResponse
     */
    public function updateMatchAction(Request $request)
    {
        return new JsonResponse([]);
    }
}
