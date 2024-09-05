# Data export

Data export plugin for Leantime

## Installation

Download a release from
<https://github.com/ITK-Leantime/leantime-dataexport/releases> and extract into
your Leantime plugins folder, e.g.

``` shell
curl --silent --location https://github.com/ITK-Leantime/leantime-dataexport/releases/download/0.0.2/leantime-dataexport-0.0.2.tar.gz | tar xv
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/dataexport --no-interaction
bin/leantime plugin:enable leantime/dataexport --no-interaction
```

## Usage

Go to Company > All Timesheets (`/timesheets/showAll`) and enjoy the new
exports:

![Export buttons](docs/images/export-buttons.png)

**Note**: Go to Settings (`/users/editOwn#settings`) and save to refresh
plugin translations.

## Development

Clone this repository into your Leantime plugins folder:

``` shell
git clone https://github.com/itk-leantime/leantime-dataexport app/Plugins/DataExport
```

Install plugin dependencies:

``` shell
cd app/Plugins/DataExport
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest composer install --no-dev
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/dataexport --no-interaction
bin/leantime plugin:enable leantime/dataexport --no-interaction
```

### Coding standards

``` shell
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest composer install
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest composer coding-standards-apply
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest composer coding-standards-check
```

```shell
docker run --rm -v "$(pwd):/work" tmknom/prettier --write assets
docker run --rm -v "$(pwd):/work" tmknom/prettier --check assets
```

```shell
docker run --rm --volume $PWD:/md peterdavehello/markdownlint markdownlint --ignore vendor --ignore LICENSE.md '**/*.md' --fix
docker run --rm --volume $PWD:/md peterdavehello/markdownlint markdownlint --ignore vendor --ignore LICENSE.md '**/*.md'
```

### Code analysis

```shell
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest composer install
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest composer code-analysis
```

## Release

We use GitHub Actions to build releases (cf. `.github/workflows/release.yaml`).
To test building a release, run

```shell
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.3-fpm:latest bin/create-release dev-test
```
