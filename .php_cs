<?php

$finder = PhpCsFixer\Finder::create()
   ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        'php_unit_test_class_requires_covers' => false,
        'ordered_imports' => [ 'sort_algorithm' => 'length' ],
    ])
    ->setFinder($finder)
;