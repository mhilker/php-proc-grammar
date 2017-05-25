<?php

namespace PhpProcGrammar;

class Grammar
{
    /**
     * @var Rule[]
     */
    private $rules;

    /**
     * @param string $name
     * @return Rule
     */
    public function rule(string $name): Rule
    {
        $rule = $this->rules[$name] ?? null;

        if ($rule === null) {
            $rule = new Rule($this, $name);
            $this->rules[$name] = $rule;
        }

        return $rule;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->rules[$name]);
    }
}
