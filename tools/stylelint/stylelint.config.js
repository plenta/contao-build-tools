'use strict';

let config = {
    extends: [
        "stylelint-config-standard-scss",
    ],
    rules: {
        "no-duplicate-selectors": null,
        "declaration-block-no-duplicate-properties": [
            true,
            {
                ignore: ["consecutive-duplicates-with-different-syntaxes"]
            }
        ],
        "no-descending-specificity": null,
        "selector-class-pattern": null,
        "selector-id-pattern": null,
        "custom-property-pattern": null,
        "scss/dollar-variable-pattern": null,
        "comment-whitespace-inside": "always",
        "selector-pseudo-element-colon-notation": "single",
        "scss/double-slash-comment-whitespace-inside": "always",
        "font-family-no-missing-generic-family-keyword": [
            true,
            { "ignoreFontFamilies": ["icomoon"] }
        ],
        "declaration-empty-line-before": null,
        "at-rule-empty-line-before": [
            "always",
            {
                except: [
                    "blockless-after-same-name-blockless",
                    "first-nested"
                ],
                ignore: [
                    "after-comment"
                ],
                ignoreAtRules: [
                    "include",
                    "extend",
                    "else"
                ]
            }
        ],
        "import-notation": "string",
        "max-nesting-depth": null,
        "selector-no-qualifying-type": null,
        "no-empty-source": null,
        "block-no-empty": null
    }
};

const fs = require('fs');
if (fs.existsSync('.stylelintrc')) {
    const merge = require('deepmerge');
    config = merge(config, JSON.parse(fs.readFileSync('.stylelintrc')));
}

module.exports = config;
