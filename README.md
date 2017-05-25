# PHP Procedural Grammar

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Rule based context sensitive grammar for procedural generation.
This is a port of [dattasid/ProcGrammar][link-source].

## Install

``` bash
$ composer require mhilker/php-proc-grammar
```

## Example

Create a new Context. The Context is a datastructure which holds our generated data.

``` php
$context = new PhpProcGrammar\Context();
```

Create a new Grammar. The grammar holds our rules.

``` php
$grammar = new PhpProcGrammar\Grammar();
```

Add all necessary rules to our grammar.
 
``` php
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
```

Fire the "root" rule.

``` php
$grammar->rule('robot')->fire($context);
```

Transform the output to an array.

``` php
$transformer = new PhpProcGrammar\Transformer\Transformer();
$data = $transformer->dotToArray($context);
print_r(json_encode($data, JSON_PRETTY_PRINT));
```

Please see [the source](./resources/example/simple.php) for a complete example.

## Result

``` json
{
    "name": "7810-36",
    "type": "This robot looks like a box.",
    "mass": {
        "unit": "kg",
        "value": 106
    }
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email `maikhilker89@gmail.com` instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mhilker/php-proc-grammar.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mhilker/php-proc-grammar.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mhilker/php-proc-grammar
[link-downloads]: https://packagist.org/packages/mhilker/php-proc-grammar
[link-source]: https://github.com/dattasid/ProcGrammar
