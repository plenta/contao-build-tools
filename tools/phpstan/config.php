<?php

declare(strict_types = 1);

$includes = [__DIR__.'/config.neon'];
$files = [
    'phpstan.neon',
    'phpstan.neon.dist',
    'phpstan.dist.neon',
    'phpstan-baseline.neon',
];

foreach ($files as $file) {
    if (file_exists(getcwd().'/'.$file)) {
        $includes[] = getcwd().'/'.$file;
        break;
    }
}

if (file_exists(getcwd().'/phpstan_full.neon')) {
    $includes = [getcwd().'/phpstan_full.neon'];
}

$config = [];
$config['includes'] = $includes;

return $config;
