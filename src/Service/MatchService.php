<?php

namespace App\Service;


use App\Entity\Match;

class MatchService
{

    public function playGame(Match $match)
    {
        $hostScore = $match->getTeamHost()->getStrength() * rand(3, 10);
        $guestScore = $match->getTeamGuest()->getStrength() * rand(1, 8);

        $avg = round(($hostScore + $guestScore) / 2);
        $avg = $avg * rand(3, 7) / 5;
        if ($hostScore < $avg) {
            $goalHost = 0;
        } else {
            $goalHost = ceil(($hostScore - $avg) / 5);
        }


        if ($guestScore < $avg) {
            $goalGuest = 0;
        } else {
            $goalGuest = ceil(($guestScore - $avg) / 5);
        }

        $match->setPlayedAt(new \DateTime());
        $match->setGoalsGuest($goalGuest);
        $match->setGoalsHost($goalHost);

        return $match;
    }

}