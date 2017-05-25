<?php

namespace PhpProcGrammar;

use PhpProcGrammar\Common\Fires;
use PhpProcGrammar\Common\Factor;
use PhpProcGrammar\Production\MultiProduction;
use PhpProcGrammar\Production\Production;
use PhpProcGrammar\Production\SequenceProduction;
use PhpProcGrammar\Production\Util\ProductionChance;
use PhpProcGrammar\Util\Random;
use Exception;

class Rule
{
    /**
     * @var Grammar
     */
    private $grammar;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $pushName = null;

    /**
     * @var Production[]
     */
    private $productions = [];

    /**
     * @var Fires
     */
    private $fires;

    /**
     * @param Grammar $grammar
     * @param string $name
     */
    public function __construct(Grammar $grammar, string $name)
    {
        $this->grammar = $grammar;
        $this->name = $name;
        $this->fires = new Fires();
    }

    /**
     * Produziert eine Liste von Rules
     *
     * @param array $ruleNames
     * @return Production
     */
    public function produces(...$ruleNames): Production
    {
        $production = new SequenceProduction($this, $ruleNames);
        $this->productions[] = $production;
        return $production;
    }

    /**
     * Produzierte eine Regel aus einer Liste von Regeln
     *
     * @param array $ruleNames
     * @return MultiProduction
     */
    public function producesOneOf(...$ruleNames): MultiProduction
    {
        $collection = new MultiProduction($this);

        foreach ($ruleNames as $ruleName) {
            $production = new SequenceProduction($this, $ruleName);
            $this->productions[] = $production;
            $collection->add($production);
        }

        return $collection;
    }

    /**
     * @param string $nodeName
     * @return Rule
     * @throws Exception
     */
	public function pushNode(string $nodeName): Rule
    {
        if ($this->pushName !== null) {
            throw new Exception('Can not change the pushname after it was already set.');
        }

        $this->pushName = $nodeName;
        return $this;
    }

    /**
     * @param Context $context
     * @return void
     */
    public function fire(Context $context): void
    {
        if ($this->pushName !== null) {
            $context->push($this->pushName);
        }

        $weights = array_map(function (Production $production) use ($context) {
            return $production->calculateWeight($context);
        }, $this->productions);

        if ($this->fires->multipleTimes() === false) {
            $randomIndex = Random::choose($weights);
            $production = $this->productions[$randomIndex];

            $min = $this->fires->getMultipleTimesMin($production)->value($context);
            $max = $this->fires->getMultipleTimesMax($production)->value($context);

            if ($min === 1 && $max === 1) {
                $production->fire($context);
            } else {
                $randomRolls = mt_rand($min, $max);
                for ($i = 0; $i < $randomRolls; $i++) {
                    $context->push($i);
                    $context->index = $i;
                    $production->fire($context);
                    $context->pop();
                }
            }
        } else {
            /** @var ProductionChance[] $successList */
            $successList = [];
            $weight = $this->fires->getChance();
            for ($i = 0; $i < count($this->productions); $i++) {
                $weights[$i] *= $weight;
                if ($weights[$i] > 1.0) {
                    $weights[$i] = 1.0;
                }
                if ($weights[$i] < 0.0) {
                    $weights[$i] = 0.0;
                }

                $production = $this->productions[$i];
                $min = $this->fires->getMultipleTimesMin($production)->value($context);
                $max = $this->fires->getMultipleTimesMax($production)->value($context);
                $randomRolls = mt_rand($min, $max);

                for ($j = 0; $j < $randomRolls; $j++) {
                    if ($weights[$i] === 0.0) {
                        continue;
                    }

                    $chance = Random::betweenZeroAndOne()/ $weights[$i];
                    $successList[] = new ProductionChance($chance, $production);
                }

                usort($successList, function (ProductionChance $a, ProductionChance $b) {
                    return $a->getPercentage() <=> $b->getPercentage();
                });

                $fired = 0;
                foreach ($successList as $productionChance) {

                    // found: must not fire
                    if (!$productionChance->isSuccess()) {
                        break;
                    }

                    // found: has fired max rules
                    if ($this->fires->atMost() > 0 && $fired >= $this->fires->atMost()) {
                        break;
                    }

                    // found: has fired min rules
                    if ($fired >= $this->fires->atLeast() && $productionChance->getPercentage() > 1.0) {
                        break;
                    }

                    $context->push($fired);
                    $context->index = $fired;
                    $productionChance->getProduction()->fire($context);
                    $context->pop();

                    $fired++;
                }
            }
        }

        if ($this->pushName !== null) {
            $context->pop();
        }
    }

    /**
     * @return Rule
     */
    public function firesMany(): Rule
    {
        $this->fires->setMultipleTimes(true);
        return $this;
    }

    /**
     * @param callable|float $chance
     * @return $this
     */
    public function firesWithChance($chance)
    {
        $this->fires->setChance($chance);
        return $this;
    }

    /**
     * @param int|callable $factor
     * @return Rule
     */
    public function firesAtLeast($factor): Rule
    {
        $this->fires->setAtLeast(new Factor($factor));
        return $this;
    }

    /**
     * @param int|callable $factor
     * @return Rule
     */
    public function firesAtMost($factor): Rule
    {
        $this->fires->setAtMost(new Factor($factor));
        return $this;
    }

    /**
     * @internal
     * @param Production $production
     * @param Factor $min
     * @param Factor $max
     * @return void
     */
    public function firesMultipleTimes(Production $production, Factor $min, Factor $max): void
    {
        $this->fires->addMultipleTimes($production, $min, $max);
    }

    /**
     * @internal
     * @return Grammar
     */
    public function getGrammar(): Grammar
    {
        return $this->grammar;
    }
}
