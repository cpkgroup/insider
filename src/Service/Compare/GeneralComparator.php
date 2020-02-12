<?php

namespace App\Service\Compare;


class GeneralComparator implements Comparator
{
    public function compare($a, $b): int
    {
        if ($b['PTS'] != $a['PTS']) {
            return $b['PTS'] <=> $a['PTS'];
        }

        if ($b['GD'] != $a['GD']) {
            return $b['GD'] <=> $a['GD'];
        }

        return $b['GF'] <=> $a['GF'];
    }
}
