<?php

namespace PhpProcGrammar\Production\Util;

use PhpProcGrammar\Production\Production;

class ProductionChance
{
    /**
     * @var float
     */
    private $percentage;

    /**
     * @var Production
     */
    private $production;

    /**
     * @param float $percentage
     * @param Production $production
     */
    public function __construct(float $percentage, Production $production)
    {
        $this->percentage = $percentage;
        $this->production = $production;
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }

    /**
     * @return Production
     */
    public function getProduction(): Production
    {
        return $this->production;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->percentage > 0.0;
    }
}
