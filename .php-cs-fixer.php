<?php

$rules = [
    '@Symfony' => true,

    /** Array Notation */
    'whitespace_after_comma_in_array' => [
        'ensure_single_space' => true,
    ],

    /** Cast Notation */
    'cast_spaces' => [
        'space' => 'none',
    ],

    /** Class Notation */
    'ordered_class_elements' => [
        'case_sensitive' => false,
        'sort_algorithm' => 'none',
        'order' => [
            'use_trait',
            'case',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public',
            'method_protected',
            'method_private',
        ],
    ],
    // 'class_definition' => true,

    /** Function Notation */
    'method_argument_space' => [
        'after_heredoc' => false,
        'attribute_placement' => 'standalone',
        'keep_multiple_spaces_after_comma' => false,
        'on_multiline' => 'ensure_fully_multiline',
    ],

    /** List Notation */
    'list_syntax' => [
        'syntax' => 'short',
    ],

    /** Return Notation */
    'no_useless_return' => true,

    /** String Notation */
    'explicit_string_variable' => true,

    /** Control Structure */
    'yoda_style' => false,
    'no_useless_else' => true,

    /** Import */
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => null,
        'import_functions' => null,
    ],

    /** Language Construct */
    'combine_consecutive_unsets' => true,
    'declare_equal_normalize' => [
        'space' => 'single',
    ],

    /** Operator */
    'concat_space' => [
        'spacing' => 'one',
    ],
    'ternary_to_null_coalescing' => true,

    /** Semicolon */
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],

    /** Whitespace */
    'array_indentation' => true,
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'return',
            'throw',
            'try',
        ],
    ],
    'method_chaining_indentation' => true,

    /** PHPDoc */
    'phpdoc_to_comment' => false,
    'phpdoc_var_annotation_correct_order' => true,
    // 'phpdoc_add_missing_param_annotation' => true,
];

$finder = PhpCsFixer\Finder::create();

// ignore laravel blade file
$finder->exclude(['vendor'])
    ->notName('*.blade.php');

return (new PhpCsFixer\Config())
    ->setRules($rules)
    // ->setIndent("\t")
    ->setLineEnding("\n")
    ->setFinder($finder);
