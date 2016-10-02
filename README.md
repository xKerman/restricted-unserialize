# restricted-unserialize

This composer package provides `unserialize` function that is safe for [PHP Obejct Injection (POI)](https://www.owasp.org/index.php/PHP_Object_Injection).

If normal `unserialize` function is used for deserilizing user input in your PHP application:

1. Don't use this package, use `json_decode` in order to avoid PHP Object Injection
2. If compatibility matters, first use this function and then try to use `json_decode` in the near future


## Why POI-safe?

`unserialize` function in this package only deserializes boolean, integer, floating point number, string, and array, and not deserilizes object instance.
Since any instances that has magic method for POP chain (such as `__destruct` or `__toString`) cannot instantiate, any plan to exploit POP chain just fails.
( You can read detailed explanation of POP chain https://www.insomniasec.com/downloads/publications/Practical%20PHP%20Object%20Injection.pdf )



## Installation

```
$ composer require xKerman/restricted-unserialize
```


## How to use

if your PHP version > 5.5:

```
use function xKerman\Restricted\unserialize;

var_dump(unserialize($data));
```

if your PHP version =< 5.5:

```
use xKerman\Restricted;

var_dump(Restricted\unserialize($data));
```


## [WIP] Related other packages

### https://github.com/MikeGarde/unserialize-fix

This package provide `\unserialize\fix` function that tries to use `unserialize` function first.  So the function is not POI-safe.

### https://github.com/academe/SerializeParser
### https://github.com/jeroenvdheuvel/serialization


## LICENSE

MIT License
