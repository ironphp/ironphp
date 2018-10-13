<h1 align="center">IronPHP</h1>
<p align="center">
    <a href="https://packagist.org/packages/ironphp/ironphp" target="_blank">
        <img alt="Total Downloads" src="https://poser.pugx.org/ironphp/ironphp/d/total.svg">
    </a>
    <a href="https://packagist.org/packages/ironphp/ironphp" target="_blank">
        <img alt="Latest Stable Version" src="https://poser.pugx.org/ironphp/ironphp/v/stable.svg">
    </a>
    <a href='https://coveralls.io/github/ironphp/ironphp?branch=master'>
        <img alt='Coverage Status' src='https://coveralls.io/repos/github/ironphp/ironphp/badge.svg?branch=master'>
    </a>
    <a href="https://travis-ci.org/ironphp/ironphp" target="_blank">
        <img alt="Build Status" src="https://api.travis-ci.org/ironphp/ironphp.svg">
    </a>
    <a href="https://opensource.org/licenses/MIT" target="_blank">
        <img alt="Software License" src="https://poser.pugx.org/ironphp/ironphp/license.svg">
    </a>
</p>

## About IronPHP

IronPHP is a development framework for PHP which
uses Front Controller, and MVC.

> **Note:** Under Development - First Beta version will be released soon.

## Table of Contents

- [Requirements](#requirements)
- [Installing IronPHP via Composer](#installing-ironphp-via-composer)
- [Update](#update)
- [Configuration](#configuration)
- [Community](#community)
- [Get Support](#get-support)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Requirements

IronPHP requires PHP 5.5 or later; we recommend using the latest PHP version whenever possible.

## Installing IronPHP via Composer

You can install IronPHP as your project using
[Composer](https://getcomposer.org)  as
a starting point. you can run the following:

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist IronPHP/IronPHP [dir_name]`.


If Composer is installed globally, run

``` bash
$ composer create-project ironphp/ironphp -s dev
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist ironphp/ironphp -s dev myapp
```

You can now use php developement webserver to view the default home page:

```bash
php jarvis serve
```

Then visit `http://localhost:8000` to see the welcome page.

## Update

Since this is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades atleast for NOT FOR NOW, so you have to do any updates manually.

## Configuration

Read and edit `'Configuraton'` in `config/app.php` and setup the `'Datasource'` in `config/database.php`.

## Community

* Follow us on [GitHub][1]

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

## Credits

- [Gaurang Parmar](https://github.com/gaurangkumar)

## License

The IronPHP framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

[1]: https://github.com/ironphp