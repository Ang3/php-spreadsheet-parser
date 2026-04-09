<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/php-spreadsheet-parser
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Component\Spreadsheet\Formatter;

use Ang3\Component\Spreadsheet\Context;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

interface CellValueFormatterInterface
{
    public function extract(Cell $cell, Context $context): mixed;
}
