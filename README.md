<p align="center">
	<!--<a href="https://packagist.org/packages/tcgdex/sdk">
		<img src="https://img.shields.io/packagist/v/tcgdex/sdk?include_prereleases&style=flat-square" alt="Packagist Version">
	</a>-->
	<a href="https://packagist.org/packages/tcgdex/sdk">
		<img src="https://img.shields.io/packagist/dm/tcgdex/sdk?style=flat-square" alt="NPM Downloads">
	</a>
	<a href="https://app.codecov.io/gh/tcgdex/javascript-sdk/">
		<img src="https://img.shields.io/codecov/c/github/tcgdex/php-sdk?style=flat-square&token=MCENGBDCXN" alt="npm version">
	</a>
		<a href="https://github.com/tcgdex/php-sdk/blob/master/LICENSE.md">
		<img src="https://img.shields.io/github/license/tcgdex/php-sdk?style=flat-square" alt="the TCGdex PHP SDK is released under the MIT license." />
	</a>
	<a href="https://github.com/tcgdex/javascript-sdk/blob/master/LICENSE.md">
		<img src="https://img.shields.io/github/workflow/status/tcgdex/php-sdk/Build%20&%20Test?style=flat-square" alt="the TCGdex PHP SDK's automated builds." />
	</a>
	<a href="https://discord.gg/NehYTAhsZE">
		<img src="https://img.shields.io/discord/857231041261076491?color=%235865F2&label=Discord&style=flat-square" alt="Discord Link">
	</a>
</p>

# TCGdex PHP SDK (WIP)

This is the SDK used to communicate with the Open source [TCGdex API](https://www.github.com/tcgdex/cards-database) trough PHP

Full API/SDK documentation in progress at https://www.tcgdex.net/docs

## Install

```bash
composer require tcgdex/sdk
# if you have no PSR 16/17/18 implementations add the following packages
composer require symfony/cache nyholm/psr7 kriswallsmith/buzz
# they will be automaticly setup for the project
# symfony/cache      === PSR16
# nyholm/psr7        === PSR17
# kriswallsmith/buzz === PSR18
```

## Usage

_Note: a complete documentation is in progress_

```php
use TCGdex\TCGdex;

// Is you are using your own PSRs implementations add theses before loading the class
TCGdex::$cache = /* PSR16 CacheInterface */;
TCGdex::$requestFactory = /* PSR17 RequestFactoryInterface */;
TCGdex::$responseFactory = /* PSR17 ResponseFactoryInterface */;
TCGdex::$client = /* PSR18 ClientInterface */;

// initialize the SDK with the language
$tcgdex = new TCGdex("en");

// Fetch you cards !
$card = $tcgdex->fetchCard('1', 'Sword & Shield');
```
