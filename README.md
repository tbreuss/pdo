# tebe\pdox

Provides an extension to the native PDO along with additional functionality. 
Because ExtendedPdo is an extension of the native PDO, code already using the native PDO or typehinted to the native PDO can use ExtendedPdo without any changes.

Added functionality in `tebe\pdox` over the native PDO includes:

- New fetch*() methods. The new fetch*() methods provide for commonly-used fetch actions.
- New fetchAll*() methods. The new fetchAll*() methods provide for commonly-used fetch all actions.
- Exceptions by default. `tebe\pdox` starts in the ERRMODE_EXCEPTION mode for error reporting instead of the ERRMODE_SILENT mode.

## Installation and Autoloading

This package is installable and PSR-4 autoloadable via Composer as `tebe/pdox`.

Alternatively, download a release, or clone this repository, then map the `tebe\pdox\` namespace to the package `src/` directory.

## Dependencies

This package requires PHP 8.1 or later; it has also been tested on PHP 8.1-8.3. We recommend using the latest available version of PHP as a matter of principle. `tebe\pdox` doesn't depend on other external packages.

## Quality

This project adheres to [Semantic Versioning](https://semver.org/).

To run the functional tests at the command line, issue `composer install` and then `composer test` at the package root. (This requires [Composer](https://getcomposer.org/) to be available as `composer`.)

This package attempts to comply with [PSR-1](https://www.php-fig.org/psr/psr-1/), [PSR-4](https://www.php-fig.org/psr/psr-4/), and [PSR-12](https://www.php-fig.org/psr/psr-12/). If you notice compliance oversights, please send a patch via pull request.

## Documentation

The documentation will follow shortly, in the meantime please refer to the [tests/](https://github.com/tbreuss/pdox/tree/main/tests) directory.
