# Food Tracker Application - Yii2 MVP

This document outlines the development plan for a minimal viable product (MVP) of a food tracking application built
using the Yii2 framework.

Read about it in the [MVP](mvp.md)

## Application Overview

The application allows users to log meals, including uploading images for analysis, and view daily nutritional
summaries. This MVP focuses on core functionality, prioritizing ease of development and rapid iteration. Social login is
not included in this version.

## Development Setup

> [!IMPORTANT]
> If you have any problems with these steps please [create an issue](../../issues/new)

You will need a few items locally before setting up. Everything runs in `docker` except for the first few setup items.
On your host download or make sure you have:

* [PHP 8.3+](https://www.php.net)
* [composer](https://getcomposer.org/)
* [docker](https://docs.docker.com/desktop/)

After having the necessary software then you can perform the following steps to setup your test instance:

1. `git clone git@github.com:cgsmith/calorie.git`
2. `cd calorie`
3. `composer install`
4. `docker compose up -d`
5. `php init --env=Development`

You're application should be running at http://localhost:20080!

### Database initialization

1. `docker exec -it calorie-frontend-1 bash`
2. `yii migrate`
3. `yii fixture/load "*"` (creates admin@example.com with password of `password`)

🎉 You should be able to login!

### Setting up Xdebug Locally

Xdebug is installed and configured on the docker container.
In [PhpStorm](https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html#integrationWithProduct) you will need
to still configure it.

#### PhpStorm Setup

1. `Ctrl + Alt + S` to open settings
2. Goto `PHP > Servers`
3. Add a new server called 'Calorie'
    1. Host: `localhost`
    2. Port: `20080`
    3. Check `Use path mappings`
    4. Map the repo to the app folder: `~/calorie -> /app`
4. Under `PHP > Debug` in the settings screen add the following ports to listen on: `9005`

You can add the port by adding a comma to separate.

#### VSCode setup

1. Open extensions `Ctrl + Shift + X`
2. Download PHP Debug extension (publisher is xdebug.org)
3. Goto `Run > Add Configuration` menu
4. Select PHP
5. Change the port setting to `9005`

Your VSCode IDE is now ready to start receiving Xdebug signals! For more documentation on setup please
see [Xdebug extension documentation](https://github.com/xdebug/vscode-php-debug)

## Testing

> [!NOTE]
> Tests should run within the docker container
> Run with `docker exec -e XDEBUG_MODE=off calorie-frontend-1 ./vendor/bin/codecept run` from your
> host.

For running tests the project uses [Codeception](https://codeception.com). To run these tests just run `composer test`.
You can also run this by running `./vendor/bin/codecept run` which will take the entire `codeception.yml` and run the
tests.

These will also run automatically on deployment.

## Deployment

> [!IMPORTANT]
> Follow Semantic Versioning and update the CHANGELOG when making a release! Sentry manages releases with the SHA
> from git - while we manage the release with version numbers in a sane way.

[Deployer](https://deployer.org) is used for the atomic deployments. An atomic deployment simply changes the symlink for
the webserver and then restarts the webserver after running any database migrations. This process, like all processes,
can always be improved upon. An atomic deployment allows a server administrator to symlink to a prior version of working
code as long as they navigate to the correct git SHA and change the symlink.

Deployer can be run from the command line with a command like below:

** Deploy to testing **

```shell
./vendor/bin/dep deploy test.calorie
```

** Deploy to production **

```shell
./vendor/bin/dep deploy calorie  --tag=1.0.0 # change your tag here
```
