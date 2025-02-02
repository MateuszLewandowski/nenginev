<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('storage')
    ->exclude('public')
;

return (new Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => false,
        'yoda_style' => ['equal' => true, 'identical' => false, 'less_and_greater' => false],
        'single_line_empty_body' => true,
        'class_definition' => [
            'single_line' => false,
            'multi_line_extends_each_single_line' => true,
            'single_item_single_line' => false,
        ],
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'global_namespace_import' => [
            'import_classes' => true,
        ],
        'no_unused_imports' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'phpdoc_no_empty_return' => false,
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'next',
        ],
    ])
    ->setFinder($finder)
;
