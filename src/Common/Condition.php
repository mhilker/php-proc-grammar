<?php

namespace PhpProcGrammar\Common;

use PhpProcGrammar\Context;
use Exception;

class Condition
{
    /**
     * @var bool|callable
     */
    private $value;

    /**
     * @param bool|callable $value
     * @throws Exception
     */
    public function __construct($value)
    {
        if (!is_bool($value) && !is_callable($value)) {
            throw new Exception('Condition must consist of a bool or callable');
        }

        $this->value = $value;
    }

    /**
     * @param Context $context
     * @return bool
     */
    public function value(Context $context)
    {
        if (is_callable($this->value)) {
            return ($this->value)($context);
        }

        return $this->value;
    }
}
