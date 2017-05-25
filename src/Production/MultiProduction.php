<?php

namespace PhpProcGrammar\Production;

class MultiProduction
{
    /**
     * @var Production[]
     */
    private $productions = [];

    /**
     * @param Production $production
     */
    public function add(Production $production)
    {
        $this->productions[] = $production;
    }

    /**
     * @param array $ruleNames
     * @return MultiProduction
     */
    public function then(...$ruleNames)
    {
        foreach ($this->productions as $production) {
            $production->then(...$ruleNames);
        }

        return $this;
    }
}
