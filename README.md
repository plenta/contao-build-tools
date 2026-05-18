# terminal42 Contao Build Tools

This is an experimental repository to ease configuration of Contao bundles and websites.

**DO NOT USE IN PRODUCTION**

## Summary

This repo contains some highly opinionated configurations for our extensions and websites.
The CQ and CS tools currently assume that your bundle or application is set up according to
Symfony Best Practice for [applications][SFBP] or [bundles][SBPB], meaning there is an `src/`
directory where all your application or bundle code lives in, but none of the configuration.


## Code Quality and Code Style

This package automatically configures the root project for code quality and code style tools.
Whenever you run `composer install` or `composer update` on the project, it will also update
the build tools automatically. The following tools are currently available and can be executed
through the `composer run` command:

### Code Style Fixer

The `cs-fixer` script will fix the coding style in the `src/` directory according to the 
latest Contao coding standards. Create an `ecs.php` script in your project
to extend the default configuration.

You can extend the default configuration by adding a `ecs.php` file to your project root.

### Rector

The `rector` script will automatically upgrade the code to match the latest Contao standards.

You can extend the default configuration by adding a `rector.php` file to your project root.

### PHPStan

The `phpstan` script will check your code with PHPStan.

You can extend the default configuration by adding a `phpstan.neon` file to your project root.

### Stylelint

The `stylelint` script will check your CSS formatting with [Stylelint](https://stylelint.io).

You can extend the default configuration by adding a `.stylelintrc` file to your project root.


### Ideas

Ideas for additional tools that could be integrated:
 - maglnet/composer-require-checker
 - https://github.com/VincentLanglet/Twig-CS-Fixer

## Overwriting Configs
The plugin automatically scans the default Contao directories. You can override the paths used by individual tools by adding configuration under `extra.contao-build-tools.tools` in your project's `composer.json`.

```json
{
    "extra": {
        "contao-build-tools": {
            "tools": {
                "ecs": {
                    "default": ["./custom-path"],
                    "contao": ["./custom-path"],
                    "template": ["./custom-path"]
                },
                "rector": {
                    "config": ["./custom-path"]
                },
                "phpstan": {
                    "config": ["./custom-path"]
                },
                "stylelint": {
                    "stylelint.config.js": {
                    }
                },
                "eslint": {
                    "eslint.config.js": {
                    }
                },
                "biome": {
                    "biome.json": {
                        "./custom-path": "./custom-path/**/*.js"
                    }
                },
                "twig-cs-fixer": {
                    "config": ["./custom-path"]
                }
            }
        }
    }
}
```

If you want to customise the paths used by Composer Dependency Analyser, create a `composer-dependency-analyser.php` file in your project's root directory and return a `Configuration` instance.
This completely replaces the default `pathsToScan` configuration provided by the plugin.

```php

<?php
# composer-dependency-analyser.php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

$config = new Configuration();

$paths = [
    './custom-path' => false, // production code
    './contao' => false,
    './templates' => false,
    './config' => false,
    './tests' => true, // development-only code
];

foreach ($paths as $path => $isDev) {
    if (file_exists($path)) {
        $config->addPathToScan($path, $isDev);
    }
}

return $config;

```

## Local Development

To use the build tools locally, you first need to install the required npm dependencies. You can add the following script to your project's `package.json` to update all bundled tool dependencies.

```json
{
    "scripts": {
        "update-build-tools": "(cd vendor/plenta/contao-build-tools/tools/biome && npm update) && (cd vendor/plenta/contao-build-tools/tools/eslint && npm update) && (cd vendor/plenta/contao-build-tools/tools/stylelint && npm update)"
    }
}
```

Then run:
```bash
npm run update-build-tools
```

## Continuous Integration

To make sure your code is always up-to-date, you might want
to run all build tools at once but only verify and not fix files.
Run `composer run build-tools` to do this.

### Example GitHub Action

```yaml
# /.github/workflows/ci.yml
name: CI

on:
    push: ~
    pull_request: ~

permissions: read-all

jobs:
    ci:
        uses: 'terminal42/contao-build-tools/.github/workflows/build-tools.yml@main'
```



## Deploying Contao websites

We use [Deployer](Deployer) do deploy Contao website to live servers.

To use the Deployer helper, you first need to require Deployer in your `composer.json`

```json
{
    "require-dev": {
        "terminal42/contao-build-tools": "dev-main",
        "deployer/deployer": "^7.0"
    }
}
```

**Example `deploy.php`**

```php
<?php

require_once 'vendor/terminal42/contao-build-tools/src/Deployer.php';

use Terminal42\ContaoBuildTools\Deployer;

(new Deployer('example.org', 'ssh-user', '/path/to/php'))
    ->addTarget('prod', '/path/to/deployment', 'https://example.org')
    ->buildAssets()
    ->includeSystemModules()
    ->addUploadPaths(
        // some additional directory
    )
    ->run()
;
```


[Deployer]: https://deployer.org
[SFBP]: https://symfony.com/doc/current/best_practices.html
[SBPB]: https://symfony.com/doc/current/bundles/best_practices.html
