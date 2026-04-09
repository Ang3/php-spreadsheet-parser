<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/php-spreadsheet-parser
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Component\Spreadsheet;

use Ang3\Component\Spreadsheet\Enum\SpreadsheetFormat;

class Context
{
    public function __construct(public SpreadsheetFormat $format,
        public ?\DateTimeZone $timezone = null,
        public Options $options = new Options())
    {
    }
}
