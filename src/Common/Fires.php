<?php

namespace PhpProcGrammar\Common;

use PhpProcGrammar\Production\Production;
use SplObjectStorage;

class Fires
{
    /**
     * @var bool
     */
    private $multipleTimes = false;

    /**
     * @var Factor
     */
    private $atLeast;

    /**
     * @var Factor
     */
    private $atMost;

    /**
     * @var Weight
     */
    private $chance;

    /**
     * @var SplObjectStorage
     */
    private $multipleTimesMin = [];

    /**
     * @var SplObjectStorage
     */
    private $multipleTimesMax = [];

    public function __construct()
    {
        $this->chance = new Weight(1.0);

        $this->atLeast = new Factor(-1);
        $this->atMost  = new Factor(-1);

        $this->multipleTimesMin = new SplObjectStorage();
        $this->multipleTimesMax = new SplObjectStorage();
    }

    /**
     * @return bool
     */
    public function multipleTimes(): bool
    {
        return $this->multipleTimes;
    }

    /**
     * @param bool $multipleTimes
     */
    public function setMultipleTimes(bool $multipleTimes)
    {
        $this->multipleTimes = $multipleTimes;
    }

    /**
     * @return Factor
     */
    public function atLeast(): Factor
    {
        return $this->atLeast;
    }

    /**
     * @return Factor
     */
    public function atMost(): Factor
    {
        return $this->atMost;
    }

    /**
     * @param Factor $atLeast
     */
    public function setAtLeast(Factor $atLeast)
    {
        $this->atLeast = $atLeast;
    }

    /**
     * @param Factor $atMost
     */
    public function setAtMost(Factor $atMost)
    {
        $this->atMost = $atMost;
    }

    /**
     * @return Weight
     */
    public function getChance(): Weight
    {
        return $this->chance;
    }

    /**
     * @param float|callable $chance
     */
    public function setChance($chance)
    {
        $this->chance = new Weight($chance);
    }

    /**
     * @param Production $production
     * @param Factor $min
     * @param Factor $max
     */
    public function addMultipleTimes(Production $production, Factor $min, Factor $max)
    {
        $this->multipleTimesMin[$production] = $min;
        $this->multipleTimesMax[$production] = $max;
    }

    /**
     * @param Production $production
     * @return Factor
     */
    public function getMultipleTimesMin(Production $production): Factor
    {
        return $this->multipleTimesMin[$production] ?? new Factor(1);
    }

    /**
     * @param Production $production
     * @return Factor
     */
    public function getMultipleTimesMax(Production $production): Factor
    {
        return $this->multipleTimesMax[$production] ?? new Factor(1);
    }
}
