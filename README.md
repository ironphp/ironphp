<h1 align="center">IronPHP Framework</h1>
<p align="center">
    <a href="https://packagist.org/packages/ironphp/ironphp" target="_blank">
        <img alt="Total Downloads" src="https://poser.pugx.org/ironphp/ironphp/d/total.svg">
    </a>
    <a href="https://packagist.org/packages/ironphp/ironphp" target="_blank">
        <img alt="Latest Stable Version" src="https://poser.pugx.org/ironphp/ironphp/v/stable.svg">
    </a>
    <a href="https://packagist.org/packages/ironphp/ironphp" target="_blank">
        <img alt="Latest Dev Version" src="https://poser.pugx.org/ironphp/ironphp/v/unstable.svg">
    </a>
    <a href='https://coveralls.io/github/ironphp/ironphp?branch=master'>
        <img alt='Coverage Status' src='https://coveralls.io/repos/github/ironphp/ironphp/badge.svg?branch=master'>
    </a>
    <a href="https://travis-ci.org/ironphp/ironphp" target="_blank">
        <img alt="Travis-CI Build Status" src="https://api.travis-ci.org/ironphp/ironphp.svg">
    </a>
    <a href="https://ci.appveyor.com/project/gaurangkumar/ironphp/branch/master" target="_blank">
        <img alt="AppVeyor Build Status" src="https://ci.appveyor.com/api/projects/status/gaurangkumar/ironphp/branch/master?svg=true">
    </a>
    <a href="https://opensource.org/licenses/MIT" target="_blank">
        <img alt="Software License" src="https://poser.pugx.org/ironphp/ironphp/license.svg">
    </a>
</p>

## About IronPHP

IronPHP is a development framework for PHP which
uses Front Controller, and MVC.

> **Note:** No stable release yet - [IronPHP Framework alpha1](https://github.com/ironphp/ironphp/releases/tag/1.0.0-alpha1) version has been released. 

> **Note:** This repository contains the core code of the IronPHP framework. If you want to build an application using IronPHP, visit the main [IronPHP repository](https://github.com/ironphp/ironphp).

IronPHP is a web application framework for PHP which uses commonly known design patterns like Associative Data Mapping, Front Controller, and MVC. Our primary goal is to provide a structured framework that enables PHP users at all levels to rapidly develop robust web applications, without any loss to flexibility.

## Table of Contents

- [Requirements](#requirements)
- [Installing IronPHP via Composer](#installing-ironphp-via-composer)
- [Update](#update)
- [Configuration](#configuration)
- [Community](#community)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)
- [Get Support](#get-support)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Requirements

IronPHP requires PHP 5.5 or later; we recommend using the latest PHP version whenever possible.

## Installing IronPHP via Composer

You can install IronPHP into your project using
[Composer](https://getcomposer.org).

Download [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe) or update `composer self-update`.

If you're starting a new project, we
recommend using the [app skeleton](https://github.com/ironphp/app) as
a starting point. For existing applications you can run the following:

``` bash
$ composer require ironphp/ironphp:"@dev"
```

## Update

Since this is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades atleast for NOT FOR NOW, so you have to do any updates manually.

## Configuration

Read and edit `'Configuraton'` in `config/app.php` and setup the `'Datasource'` in `config/database.php`.

## Community

* Follow us on [GitHub][1]
* Follow us on [FaceBook][2]

## Contributing

* [CONTRIBUTING.md](CONTRIBUTING.md) - Quick pointers for contributing to the IronPHP project.

## Code of Conduct

* [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) - In order to ensure that the IronPHP community is welcoming to all, please review and abide by the Code of Conduct.

## About Us

IronPHP development is led by the [Gaurang Parmar](https://twitter.com/gaurangkumarp).

## Get Support

* [GitHub Issues](https://github.com/ironphp/ironphp/issues) - Got issues? Please tell us!

## Security

If you’ve found a security issue in IronPHP, please use the following procedure instead of the normal bug reporting system. Instead of using the bug tracker, mailing list or IRC please send an email to gaurangkumarp@gmail.com.

For each report, we try to first confirm the vulnerability. Once confirmed, the IronPHP will take the following actions:

- Acknowledge to the reporter that we’ve received the issue, and are working on a fix. We ask that the reporter keep the issue confidential until we announce it.
- Get a fix/patch prepared.
- Prepare a post describing the vulnerability, and the possible exploits.
- Release new versions of all affected versions.
- Prominently feature the problem in the release announcement.

## Authors

- Gaurang Parmar  | [GitHub](https://github.com/gaurangkumar)  | [Twitter](https://twitter.com/gaurangkumarp) | [Patreon](https://www.patreon.com/gaurangkumar) | <gaurangkumarp@gmail.com>

## License

The IronPHP framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

[1]: https://github.com/ironphp
[2]: https://www.facebook.com/IronPHP-Framwork-325690624644002