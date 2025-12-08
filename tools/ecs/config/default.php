<?php

declare(strict_types=1);

use Composer\Semver\VersionParser;
use Contao\EasyCodingStandard\Fixer\CommentLengthFixer;
use Contao\EasyCodingStandard\Fixer\TypeHintOrderFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([__DIR__.'/../vendor/contao/easy-coding-standard/config/contao.php']);

    $skip = [
        CommentLengthFixer::class,
        MethodChainingIndentationFixer::class => [
            '*/DependencyInjection/Configuration.php',
        ],
        HeaderCommentFixer::class,
        GeneralPhpdocAnnotationRemoveFixer::class,
        PhpdocNoAliasTagFixer::class,
        PhpdocSeparationFixer::class,
        PhpdocSummaryFixer::class
    ];

    if (file_exists(getcwd().'/composer.json')) {
        $versionParser = new VersionParser();
        $composerJson = json_decode(file_get_contents(getcwd().'/composer.json'), true, 512, JSON_THROW_ON_ERROR);

        if ($phpConstraint = $composerJson['config']['platform']['php'] ?? $composerJson['require']['php'] ?? null) {
            $parsedConstraints = $versionParser->parseConstraints($phpConstraint);

            if ($parsedConstraints->matches($versionParser->parseConstraints('< 8'))) {
                $skip[] = TypeHintOrderFixer::class;
            }
        }
    }

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, ['header' => '']);
    $ecsConfig->skip($skip);
    $ecsConfig->parallel();
    $ecsConfig->lineEnding("\n");

    $ecsConfig->cacheDirectory(sys_get_temp_dir().'/ecs_default_cache');

    if (file_exists(getcwd().'/ecs.php')) {
        $ecsConfig->import(getcwd().'/ecs.php');
    }
};
