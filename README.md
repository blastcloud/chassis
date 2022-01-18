# Chassis

<p style="text-align:center;">
    <a href="https://travis-ci.org/blastcloud/chassis">
        <img src="https://img.shields.io/github/workflow/status/blastcloud/chassis/run-tests?label=tests">
    </a>
    <img src="https://poser.pugx.org/blastcloud/chassis/v/stable" />
    <a href="https://codeclimate.com/github/blastcloud/chassis/maintainability">
        <img src="https://api.codeclimate.com/v1/badges/3f5e4fa71bd03ce8424f/maintainability" />
    </a>
    <a href="https://github.com/blastcloud/chassis/blob/master/LICENSE.md">
        <img src="https://poser.pugx.org/blastcloud/chassis/license" />
    </a>
</p>

A framework for building declarative testing libraries for PHP / HTTP request packages.

Chassis provides a foundation upon which a shared testing syntax can be used across different HTTP client projects in the PHP community (thus far [Guzzle](http://docs.guzzlephp.org/en/stable/) with [Guzzler](https://guzzler.dev) and [HttpClient](https://symfony.com/components/HttpClient) with [Hybrid](https://hybrid.guzzler.dev)).

## Requirements

- PHP 7.4+
- PHPUnit 8.2+

The one firm requirement is PHPUnit, as the testing infrastructure of Chassis is based on the PHPUnit assertion and test case classes. Chassis also only supports the versions of PHPUnit currently supported (7 and 8 at this time). As new versions of PHPUnit are delivered and support for the older versions are dropped, Chassis will drop support also.

As each new version of PHPUnit arrives, Chassis will also drop supported versions of PHP the language. When PHPUnit 9 arrives, support for PHP 7.1 will be dropped. That’s not to say that any code written in PHP 7.1 will stop working, but the project will no longer consider backwards compatibility for that language version.
