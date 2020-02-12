<?php

namespace App\Service\Compare;


class ScoreComparator implements Comparator
{
    public function compare($a, $b): int
    {
        return $b['PTS'] <=> $a['PTS'];
    }
}
