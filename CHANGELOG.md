# Changelog
All notable changes to this project will be documented in this file.

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
