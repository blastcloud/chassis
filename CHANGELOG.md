# Changelog
All notable changes to this project will be documented in this file.

## [1.1.1] - 2024-02-19
- `.gitignore` and test cache cleanup. Thanks @jdreesen.
- Type error fix for compatibility with Guzzler. Thanks @berrugo.

## [1.1.0] - 2024-02-17
- Add support back for PHPUnit 11. Now supporting 9.6, 10, and 11.
- Add support for PHP 8.3. Now supports 8.1+.

## [1.0.8] - 2023-08-07
- Correct type-hinting error.

## [1.0.7] - 2023-08-07
- Correct changes in Invocation class in PHPUnit 10.3
- Update supported PHP and PHPUnit versions

## [1.0.6] - 2022-12-27
- Support for PHP 8.2, remove support for 7.4
- Updating dependencies

## [1.0.5] - 2022-01-17
- Support for PHP 8.1
- Dropping support for versions of PHP below 7.4
- Removing Travis CI yml, and updating PHPUnit schema

## [1.0.4] - 2020-12-04
- Support for PHP 8.0
- Moves CI to Github actions

## [1.0.3] - 2020-03-09
- This release is purely to support PHPUnit 9. As a by-product of that, chassis will no longer support versions below 8.2
- Drops support for PHP 7.1

## [1.0.2] - 2019-10-03
- Fixed issues with PHPUnit 8.4  This version of PHPUnit did away with the `InvokedRecorder` class. So, now I can't really keep a firm type hint on the `expects()` method. The type hint has been removed and will have to just rely on users reading the documentation.

## [1.0.1] - 2019-09-05
- Fixed a couple compatibility issues with PHPUnit 7.0.0
- Added badges for the readme
- Updated the PHPUnit requirement to be `>=7.0` instead of 7.2 and up
- Updated the package description
- Ignored code coverage that is only not run on the PHP 7.1 version

## [1.0.0] - 2019-09-05
- Initial Release
