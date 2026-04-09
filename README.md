PHP Spreadsheet Parser
======================

[![Latest Stable Version](https://poser.pugx.org/ang3/php-spreadsheet-parser/v/stable)](https://packagist.org/packages/ang3/php-spreadsheet-parser)
[![Total Downloads](https://poser.pugx.org/ang3/php-spreadsheet-parser/downloads)](https://packagist.org/packages/ang3/php-spreadsheet-parser)

Parsing spreadsheet files thanks to the component 
[phpoffice/phpspreadsheet](https://phpspreadsheet.readthedocs.io/en/latest/).

Summary
-------

- [Installation](#installation)
- [Usage](#usage)
- [Run tests](#run-tests)


Installation
------------

Open a command console and execute the following command to download the latest stable version of this bundle:

```shell
composer require ang3/php-spreadsheet-parser
```

If you install this component outside of a Symfony application, you must require the vendor/autoload.php 
file in your code to enable the class autoloading mechanism provided by Composer. 
Read [this article](https://symfony.com/doc/current/components/using_components.html) for more details.

Usage
-----

### Basic usage

```php
<?php

require_once 'vendor/autoload.php';

use Ang3\Component\Spreadsheet\Parser;
use Ang3\Component\Spreadsheet\Context;
use Ang3\Component\Spreadsheet\Options;
use Ang3\Component\Spreadsheet\Enum\SpreadsheetFormat;

// Create parser
$parser = new Parser();

// Configure context
$context = new Context(
    format: SpreadsheetFormat::XLSX,
    options: new Options()
);

// Read file
$data = $parser->read('/path/to/file.xlsx', $context);

// Result: array indexed by sheet name, then row/column
print_r($data);
```

### Options

The Options class controls how the spreadsheet is interpreted. It is immutable and validated at construction time.

**Available Options**

| Parameter                       | Type         | Default | Description                                                                              | Comment                                                                                           |
|---------------------------------|--------------|---------|------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------|
| `hasHeaders`                    | boolean      | true    | Indicates whether the spreadsheet contains a header row.                                 | If set to false, all rows are treated as raw data.                                                |
| `firstRowIndex`                 | boolean      | true    | Defines the 1-based index of the first row to parse.                                     | -                                                                                                 |
| `headersRowIndex`               | integer      | NULL    | Defines the 1-based index of the header row.                                             | if NULL, header row will be auto-detected (first non-empty row) - Ignored if `hasHeaders` = false |
| `dataStartRowIndex`             | integer      | NULL    | Defines the 1-based index of the first data row.                                         | if NULL, automatically inferred (usually right after headers)                                     |
| `dataEndRowIndex`               | integer      | NULL    | Defines the 1-based index of the last data row.                                          | if NULL, reads until the end of the sheet                                                         |
| `convertColumnLettersToNumbers` | boolean      | false   | Converts column identifiers.                                                             | i.e. "A" → 1, "B" → 2, "AA" → 27                                                                  |
| `formulaEvaluation`             | boolean      | false   | If enabled, formula cells will return their computed value instead of the raw formula.   | -                                                                                                 |
| `formatterEnabled`              | boolean      | true    | Applies value formatting using the configured formatter: dates, numbers, custom formats. | -                                                                                                 |
| `skipEmptyRows`                 | boolean      | true    | Whether empty rows should be ignored during parsing.                                     | -                                                                                                 |

**Validation Rules**

An InvalidArgumentException if the configuration is inconsistent.

Run tests
---------

### With Docker

```$ git clone https://github.com/Ang3/php-spreadsheet-parser.git```

```$ make up```

```$ bin/test```

### In PHP environment

```$ git clone https://github.com/Ang3/php-spreadsheet-parser.git```

```$ composer install```

```$ vendor/bin/simple-phpunit```

That's it!