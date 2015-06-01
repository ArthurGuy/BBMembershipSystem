<?php namespace BB\Helpers;

class StatsHelper
{

    public static function roundToNearest($n, $x = 5)
    {
        if (round($n) % $x === 0) {
            return round($n);
        } else {
            return (round($n) - (round($n) % $x)) + (round(((round($n) % $x) * 2) / 10) * $x);
        }


    }
} 