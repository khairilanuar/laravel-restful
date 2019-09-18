<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('public')
    ->notPath('bootstrap/cache')
    ->notPath('storage')
    ->exclude('node_modules')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('.phpstorm.meta.php')
    ->notName('_ide_*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

$rules = [
    'psr0' => false,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    '@PHP71Migration' => true,
    'array_syntax' => ['syntax' => 'short'],
    'array_indentation' => true,
    'binary_operator_spaces' => ['operators' => ['=>' => 'align']],
    'blank_line_before_statement' => ['statements' => ['continue', 'declare', 'throw', 'try']],
    'increment_style' => ['style' => 'post'],
    'linebreak_after_opening_tag' => true,
    'mb_str_functions' => false,
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline',],
    'method_chaining_indentation' => true,
    'no_php4_constructor' => true,
    'no_unreachable_default_argument_value' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'not_operator_with_successor_space' => true,
    'ordered_imports' => ['sortAlgorithm' => 'alpha'],
    'php_unit_strict' => true,
    'phpdoc_order' => true,
    'simplified_null_return' => true,
    'single_blank_line_at_eof' => true,
    'strict_comparison' => false,
    'strict_param' => true,
];

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->setFinder($finder)
;
