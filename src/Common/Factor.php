<?php

namespace PhpProcGrammar\Common;

use PhpProcGrammar\Context;
use Exception;

class Factor
{
    /**
     * @var int|callable
     */
    private $value;

    /**
     * @param int|callable $value
     * @throws Exception
     */
    public function __construct($value)
    {
        if (!is_int($value) && !is_callable($value)) {
            throw new Exception('Factor must consist of an int or callable.');
        }

        $this->value = $value;
    }

    /**
     * @param Context $context
     * @return callable|int
     */
    public function value(Context $context)
    {
        if (is_callable($this->value)) {
            return ($this->value)($context);
        }

        return $this->value;
    }
}
