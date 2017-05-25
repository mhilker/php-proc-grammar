<?php

namespace PhpProcGrammar\Util;

class Random
{
    /**
     * @return float
     */
    public static function betweenZeroAndOne(): float
    {
        return mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();
    }

    /**
     * @param float $start
     * @param float $end
     * @return float
     */
    public static function between(float $start, float $end): float
    {
        $diff = $end - $start;

        return $start + ($diff * self::betweenZeroAndOne());
    }

    /**
     * @param array $weights
     * @return int
     */
    public static function choose(array $weights)
    {
        $sum = array_reduce($weights, function (float $carry, float $weight) {
            return $carry + $weight;
        }, 0);

        $choice = self::betweenZeroAndOne() * $sum;

        for ($i = 0; $i < count($weights); $i++) {
            if ($choice < $weights[$i]) {
                return $i;
            }

            $choice -= $weights[$i];
        }

        return count($weights) - 1;
    }

    /**
     * @param int $depth
     * @return float
     */
    public static function fibonacci(int $depth): float
    {
        // eliminate the first entry on the scale
        $depth += 1;

        $current  = self::calculateFibonacci($depth);
        $next = self::calculateFibonacci($depth + 1);

        return self::between($current - 1.0, $next - 1.0);
    }

    /**
     * @param int $current
     * @return int
     */
    private static function calculateFibonacci(int $current)
    {
        if ($current <= 2) {
            return $current;
        }

        return self::calculateFibonacci($current - 1) + self::calculateFibonacci($current - 2);
    }
}
