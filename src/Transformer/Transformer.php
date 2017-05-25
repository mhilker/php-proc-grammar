<?php

namespace PhpProcGrammar\Transformer;

use PhpProcGrammar\Context;

class Transformer
{
    /**
     * @param Context $context
     * @return array
     */
    public function dotToArray(Context $context)
    {
        $data = [];

        try {
            foreach ($context->getValues() as $path => $value) {
                $this->transform($data, $path, $value);
            }
        } catch (\Throwable $t) {
            print_r($context->getValues());
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $path
     * @param mixed $value
     */
    private function transform(array &$data, string $path, $value)
    {
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            $data = &$data[$key];
        }
        $data = $value;
    }
}
