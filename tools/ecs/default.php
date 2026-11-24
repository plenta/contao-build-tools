<?php

declare(strict_types=1);

use Contao\EasyCodingStandard\Fixer\CommentLengthFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$skip = [
    CommentLengthFixer::class,
    MethodChainingIndentationFixer::class => [
        '*/DependencyInjection/Configuration.php',
    ],
    GeneralPhpdocAnnotationRemoveFixer::class,
    PhpdocNoAliasTagFixer::class,
    PhpdocSeparationFixer::class,
    PhpdocSummaryFixer::class,
    Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer::class,
];

$builder = ECSConfig::configure()
    ->withSets([__DIR__.'/../code-quality-tools/vendor/terminal42/code-quality-tools/tools/ecs/config.php'])
    ->withCache(sys_get_temp_dir().'/ecs_default_cache')
    ->withSkip($skip)
;

return new class($builder) {
    public function __construct(private $builder)
    {
    }

    public function __invoke(ECSConfig $ecsConfig): void
    {
        ($this->builder)($ecsConfig);

        $rootConfigFile = getcwd().'/ecs.php';
        if (!file_exists($rootConfigFile)) {
            return;
        }

        $rootConfig = require $rootConfigFile;
        if (is_callable($rootConfig)) {
            $rootConfig($ecsConfig);
        }
    }
};
