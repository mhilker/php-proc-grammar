<?php

namespace PhpProcGrammar;

class Context
{
    /**
     * @var int|null
     */
    public $index = null;

    /**
     * @var array
     */
    private $cursor = [];

    /**
     * @var array
     */
    private $values = [];

    public function push(string $name)
    {
        $this->cursor[] = $name;
    }

    public function pop()
    {
        $this->cursor = array_slice($this->cursor, 0, -1);
    }

    public function setCurrent($value)
    {
        if ($value instanceof Context) {
            $this->mergeContext($value);
        } else {
            $key = $this->createCursorKey();
            $this->values[$key] = $value;
        }
    }

    public function setValue(string $key, $value)
    {
        $this->values[$key] = $value;
    }

    public function getValue(string $key)
    {
        return $this->values[$key] ?? null;
    }

    public function getValues()
    {
        return $this->values;
    }

    private function mergeContext(Context $other)
    {
        $cursor = $this->createCursorKey();

        foreach ($other->getValues() as $key => $value) {
            $this->setValue($cursor . '.' . $key, $value);
        }
    }

    private function createCursorKey(): string
    {
        return implode('.', $this->cursor);
    }
}