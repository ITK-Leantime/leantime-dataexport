# Data export

Data export plugin for Leantime

## Installation

Download a release from <https://github.com/ITK-Leantime/leantime-dataexport/releases> and extract into your Leantime
plugins folder, e.g.

``` shell
curl --silent --location https://github.com/ITK-Leantime/leantime-dataexport/releases/download/0.0.2/leantime-dataexport-0.0.2.tar.gz | tar xv
```

Install and enable the plugin:

``` shell
bin/leantime plugin:install leantime/dataexport --no-interaction
bin/leantime plugin:enable leantime/dataexport --no-interaction
```

## Usage

Go to Company > All Timesheets (`/timesheets/showAll`) or My Work > Timesheets (`/timesheets/showMy`) and enjoy the new
exports:

![Export buttons](docs/images/export-buttons.png)

**Note**: Go to Settings (`/users/editOwn#settings`) and save to refresh plugin translations.

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

```shell
docker run --rm --tty --volume "$PWD:/app" --workdir /app peterdavehello/shellcheck shellcheck bin/create-release
docker run --rm --tty --volume "$PWD:/app" --workdir /app peterdavehello/shellcheck shellcheck bin/deploy
```

```shell
docker compose build
docker compose run --rm php npm install
docker compose run --rm php npm run coding-standards-apply
```

### Code analysis

```shell name=dev-install
docker compose run --interactive --rm --volume ${PWD}:/app phpfpm composer install
```
```shell name=code-analysis
docker compose run --interactive --rm --volume ${PWD}:/app phpfpm composer code-analysis
```

## Test release build

```
docker compose build && docker compose run --rm php bash bin/create-release dev-test
```

The create-release script replaces `@@VERSION@@` in
[register.php](https://github.com/ITK-Leantime/leantime-dataexport/blob/f7c3992f78270c03b6fc84dbc9b1bbd6e48e53d6/register.php#L9)
and
[Services/DataExport.php](https://github.com/ITK-Leantime/leantime-dataexport/blob/f7c3992f78270c03b6fc84dbc9b1bbd6e48e53d6/Services/DataExport.php#L15)
with the tag provided (in the above it is `dev-test`).

## Deploy

The deploy script downloads a [release](https://github.com/ITK-Leantime/leantime-dataexport/releases) from Github and
unzips it. The script should be passed a tag as argument. In the process the script deletes itself, but the script
finishes because it [is still in memory](https://linux.die.net/man/3/unlink).
