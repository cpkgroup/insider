<?php

namespace App\Service\Compare;


class GoalDiffComparator implements Comparator
{
    public function compare($a, $b): int
    {
        return $b['GD'] <=> $a['GD'];
    }
}
