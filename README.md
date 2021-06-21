# TCGdex PHP SDK (WIP)

This is the SDK used to communicate with the Open source [TCGdex API](https://www.github.com/tcgdex/cards-database) trough PHP

Full API/SDK documentation in progress at https://www.tcgdex.net/docs

## Install

```bash
composer require tcgdex/sdk
# if you have no PSR 16/17/18 implementations add the following packages
# they will be automaticly setup for the project
# symfony/cache      === PSR16
# nyholm/psr7        === PSR17
# kriswallsmith/buzz === PSR18
composer require symfony/cache nyholm/psr7 kriswallsmith/buzz
```

## Usage

_Note: a complete documentation is in progress_

```php
use TCGdex\TCGdex;

// Is you are using your own PSRs implementations add theses before loading the class
TCGdex::$cache = /* PSR16 CacheInterface */;
TCGdex::$requestFactory = /* PSR17 RequestFactoryInterface */;
TCGdex::$client = /* PSR18 ClientInterface */;

// initialize the SDK with the language
$tcgdex = new TCGdex("en");

// Fetch you cards !
$card = $tcgdex->fetchCard('1', 'Sword & Shield');
```