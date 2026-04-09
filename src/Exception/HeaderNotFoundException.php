<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/php-spreadsheet-parser
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Component\Spreadsheet\Exception;

class HeaderNotFoundException extends \RuntimeException
{
    public function __construct(private readonly string $headerName)
    {
        parent::__construct(\sprintf('Header "%s" not found.', $headerName));
    }

    public function getHeaderName(): string
    {
        return $this->headerName;
    }
}
