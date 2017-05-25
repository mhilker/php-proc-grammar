<?php

namespace PhpProcGrammar\Common;

use PhpProcGrammar\Context;
use Exception;

class Weight
{
    /**
     * @var float|callable
     */
    private $value;

    /**
     * @param float|callable $value
     * @throws Exception
     */
    public function __construct($value)
    {
        if (!is_float($value) && !is_callable($value)) {
            throw new Exception('Weight must consist of an float or callable.');
        }

        $this->value = $value;
    }

    /**
     * @param Context $context
     * @return float
     */
    public function value(Context $context)
    {
        if (is_callable($this->value)) {
            return ($this->value)($context);
        }

        return $this->value;
    }
}
