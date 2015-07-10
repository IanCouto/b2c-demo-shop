<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/src')
    ->exclude('Generated')
;
return Symfony\CS\Config\Config::create()
    ->level(\Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(
        array(
            'elseif',
            'eof_ending',
            'extra_empty_lines',
            'concat_with_spaces',
            'function_declaration',
            'include',
            'indentation',
            'linefeed',
            'php_closing_tag',
            'psr0',
            'short_tag',
            'trailing_spaces',
            'unused_use',
            'visibility',
            'empty_enclosing_lines',
            'phpdoc_order',
            'unalign_double_arrow',
            'unalign_equals',
            'short_array_syntax',
            'strict',
            '-phpdoc_params',
            '-concat_without_spaces',
            '-pre_increment',
            '-phpdoc_indent',
            '-phpdoc_short_description',
            '-braces',
        )
    )
    ->addCustomFixer(new \SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\EmptyEnclosingLinesFixer())
    ->finder($finder)
;
