<?php
use PhpProcGrammar\Context;
use PhpProcGrammar\Grammar;
use PhpProcGrammar\Transformer\Transformer;

require __DIR__ . '/../../vendor/autoload.php';

$context = new Context();
$grammar = new Grammar();

$grammar->rule('robot')
    ->produces('name', 'type', 'mass');

$grammar->rule('name')
    ->pushNode('name')
    ->produces(function (Context $context) {
        $name = strtoupper(bin2hex(random_bytes(2)))
              . '-'
              . bin2hex(random_bytes(1));

        $context->setCurrent($name);
    });

$grammar->rule('type')
    ->pushNode('type')
    ->produces('typeHuman')->weight(1.0)
    ->produces('typeBox')->weight(0.5)
    ->produces('typeSpider')->weight(0.1);

$grammar->rule('typeHumanoid')
    ->produces('This robot looks like a human.');
$grammar->rule('typeBox')
    ->produces('This robot looks like a box.');
$grammar->rule('typeSpider')
    ->produces('This robot looks like a spider.');

$grammar->rule('mass')
    ->pushNode('mass')
    ->produces(function (Context $context) {
        $context->push('unit');
        $context->setCurrent('kg');
        $context->pop();

        $context->push('value');
        $context->setCurrent(10 + mt_rand(1, 120));
        $context->pop();
    });

$grammar->rule('robot')->fire($context);

$transformer = new Transformer();
$data = $transformer->dotToArray($context);
print_r(json_encode($data, JSON_PRETTY_PRINT));
