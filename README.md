# Data export

Data export plugin for Leantime

## Installation

Clone this repository into your Leantime plugin folder:

``` shell
git clone github.com/itk-leantime/leantime-dataexport app/Plugins/Dataexport
```

Install plugin dependencies:

``` shell
cd app/Plugins/Dataexport
docker run --tty --interactive --rm --env COMPOSER=composer.plugin.json --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer install --no-dev
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/dataexport --no-interaction
bin/leantime plugin:enable leantime/dataexport --no-interaction
```

## Development

### Coding standards

``` shell
docker run --tty --interactive --rm --env COMPOSER=composer.plugin.json --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer install
docker run --tty --interactive --rm --env COMPOSER=composer.plugin.json --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer coding-standards-check
```
