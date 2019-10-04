# Changelog
All notable changes to this project will be documented in this file.

## [1.0.0] - 2019-09-05
- Initial Release

## [1.0.1] - 2019-09-05
- Fixed a couple compatibility issues with PHPUnit 7.0.0
- Added badges for the readme
- Updated the PHPUnit requirement to be `>=7.0` instead of 7.2 and up
- Updated the package description
- Ignored code coverage that is only not run on the PHP 7.1 version

## [1.0.2] - 2019-10-03
- Fixed issues with PHPUnit 8.4  This version of PHPUnit did away with the `InvokedRecorder` class. So, now I can't really keep a firm type hint on the `expects()` method. The type hint has been removed and will have to just rely on users reading the documentation.