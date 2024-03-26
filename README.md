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
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer install --no-dev
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/dataexport --no-interaction
bin/leantime plugin:enable leantime/dataexport --no-interaction
```

### Coding standards

``` shell
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer install
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer coding-standards-check
docker run --tty --interactive --rm --volume ${PWD}:/app itkdev/php8.1-fpm:latest composer coding-standards-apply
```

```shell
docker run --tty --interactive --rm --volume ${PWD}:/app node:20 yarn --cwd /app install
docker run --tty --interactive --rm --volume ${PWD}:/app node:20 yarn --cwd /app coding-standards-check
```

## Release

We use GitHub Actions to build releases (cf. `.github/workflows/release.yaml`).
To test building a release, run

```shell
# https://github.com/catthehacker/docker_images/pkgs/container/ubuntu#images-available
# Note: The ghcr.io/catthehacker/ubuntu:full-latest image is HUGE!
docker run --rm --volume ${PWD}:/app --workdir /app ghcr.io/catthehacker/ubuntu:full-latest bin/create-release test
# Show release content
tar tvf leantime-plugin-*-test.tar.gz
```
