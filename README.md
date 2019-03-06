# ptlis/php-serialized-data-editor

Tools for manipulating PHP serialized datastructures without deserializing and re-serializing the data.

This is useful when:
* You don't or can't have the classe definitions loaded (e.g. when processing arbitrary data in a DB, Redis etc).
* You don't want to invoke the `__wakeup` method (e.g. it tries to connect to a resource not available in the execution context).

[![Build Status](https://travis-ci.org/ptlis/php-serialized-data-editor.svg?branch=master)](https://travis-ci.org/ptlis/php-serialized-data-editor) [![Code Coverage](https://scrutinizer-ci.com/g/ptlis/php-serialized-data-editor/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ptlis/php-serialized-data-editor/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ptlis/php-serialized-data-editor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ptlis/php-serialized-data-editor/?branch=master) [![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/ptlis/php-serialized-data-editor/blob/master/LICENSE) [![Latest Stable Version](https://poser.pugx.org/ptlis/php-serialized-data-editor/v/stable)](https://packagist.org/packages/ptlis/php-serialized-data-editor)


## Install

With composer:

```shell
$ composer require ptlis/php-serialized-data-editor
```


## Usage

This library currently supports basic search & replacement of string values (ignoring array keys and property names):

```php
use ptlis\SerializedDataEditor\Editor;

// Mock some serialized data
$serialized = serialize([
    'test' => 'foo',
    'test2' => 'foobar foo',
    'foo' => 'baz'
]);

$editor = new Editor();

$containsCount = $editor->containsCount($serialized, 'foo');  // $containsCount === 3

$modified = $editor->replace($serialized, 'foo', 'wibble');
/**
 * $modified when unserialized is:
 * [
 *     'test' => 'wibble',
 *     'test2' => 'wibblebar wibble',
 *     'foo' => 'baz'
 * ]
 */
```

