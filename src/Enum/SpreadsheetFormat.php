<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/php-spreadsheet-parser
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Component\Spreadsheet\Enum;

use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

enum SpreadsheetFormat: string
{
    case Excel2003 = 'xls';
    case Excel2007 = 'xlsx';

    public function getParser(): IReader
    {
        return match ($this) {
            self::Excel2003 => new Xls(),
            self::Excel2007 => new Xlsx(),
        };
    }
}
