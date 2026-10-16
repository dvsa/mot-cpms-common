# mot-cpms-common
CPMS service for MOT Testing application

## Installing

The recommended way to install is through [Composer](https://getcomposer.org/).
```
composer require dvsa/mot-cpms-common
```

## Logging Configuration

This module uses **mot-logger v3.3.1**. Logging is configured via the named
`loggers` structure under the `mot_logger` config key, introduced in BL-22896.
See the [MOT Logger](https://github.com/dvsa/mot-logger) repository for the full
configuration reference.

### Named logger

The module's default configuration in `config/module.config.php` defines a named
logger `mot-cpms-common` (the repository name — not `default`):

```php
'mot_logger' => [
    'loggers' => [
        'mot-cpms-common' => [
            'channel' => 'cpms-common',
            'writers' => [
                'stderr' => [
                    'type'      => 'stream',
                    'path'      => 'php://stderr',
                    'formatter' => 'pipe',
                    'level'     => 'error',
                    'enabled'   => true,
                ],
                'file' => [
                    'type'      => 'stream',
                    'path'      => '/var/log/dvsa/' . \date('Y-m-d') . '-cpms-common.log',
                    'formatter' => 'json',
                    'level'     => 'error',
                    'enabled'   => true,
                ],
            ],
        ],
    ],
],
```

- **Channel**: `cpms-common` — preserved from the previous `logger` configuration
- **stderr writer**: pipe-delimited output to `php://stderr` at `error` level and above
- **file writer**: JSON output to `/var/log/dvsa/{YYYY-MM-DD}-cpms-common.log` at `error` level and above

Consuming applications can override any part of this configuration in their own
`config/autoload/*.php` files, e.g. to change the file path, level or disable a
writer. Writers are keyed (`stderr`, `file`) so individual entries can be
overridden or disabled:

```php
'mot_logger' => [
    'loggers' => [
        'mot-cpms-common' => [
            'writers' => [
                'file' => ['enabled' => false],
            ],
        ],
    ],
],
```

### Custom logger setup

The `Logger` service (also available via the `logger` alias) is built by
`CpmsCommon\Service\LoggerServiceFactory`. The factory delegates to mot-logger's
`DvsaLogger\Factory\MotLoggerFactory` to build a Monolog instance from the
`mot-cpms-common` named configuration, then wraps it in
`CpmsCommon\Service\LoggerService` — a `MotLogger` subclass adding the
backward-compatible `logException()` helper used across CPMS code.

Registering the `DvsaLogger` module (shipped with `dvsa/mot-logger`) additionally
makes the `DvsaLogger\Logger\MotLogger` service and on-demand named loggers
(e.g. `$container->get('mot-cpms-common')`) available via the module's abstract
factory.

### Migrating from the legacy `logger` config

The legacy `logger` config key (`filename`/`location`/`channel`) is no longer
read. Applications overriding it in their own config should move those overrides
to `mot_logger.loggers['mot-cpms-common']` as shown above.

## Contributing

Please refer to our [Contribution Guide](/CONTRIBUTING.md).
