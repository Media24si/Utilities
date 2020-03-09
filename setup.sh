#!/bin/sh

git init
wget -nv -O .phpcs.xml https://guidelines.m24.dev/phpcs.xml
wget -nv -O .eslintrc.json https://guidelines.m24.dev/eslintrc.json
wget -nv -O .git/hooks/pre-commit https://guidelines.m24.dev/pre-commit
chmod +x .git/hooks/pre-commit
composer require "squizlabs/php_codesniffer=*" --dev
npm install eslint eslint-plugin-vue standard --save-dev