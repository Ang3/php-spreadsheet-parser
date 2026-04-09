<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/php-spreadsheet-parser
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Component\Spreadsheet;

/**
 * Configuration object for spreadsheet parsing.
 *
 * This class is immutable and defines how a spreadsheet should be interpreted,
 * including header handling, data boundaries, formatting, and normalization options.
 */
class Options
{
    /**
     * @param bool            $hasHeaders                    Whether each row should be returned as an associative structure
     *                                                       using header values as keys
     * @param int<1,max>|null $firstRowIndex                 The 1-based index of rows to parse.
     *                                                       If null, the first row is the row with index 1.
     * @param int<1,max>|null $headersRowIndex               The 1-based index of the header row.
     *                                                       If null, the header row will be automatically detected.
     *                                                       Ignored if $hasHeaders is false.
     * @param int<1,max>|null $dataFirstRowIndex             The 1-based index of the first data row.
     *                                                       If null, it will be inferred (typically the row after headers).
     * @param int<1,max>|null $dataLastRowIndex               The 1-based index of the last data row.
     *                                                       If null, reading continues until the end of the sheet.
     * @param bool            $convertColumnLettersToNumbers Whether spreadsheet column identifiers (e.g. "A", "B", "AA")
     *                                                       should be converted to zero- or one-based numeric indexes.
     * @param bool            $formulaEvaluation             Whether cell formulas should be evaluated to their computed values
     * @param bool            $formatterEnabled              Whether value formatting should be applied (e.g. dates, numbers).
     * @param bool            $skipEmptyRows                 Whether empty rows should be ignored during iteration
     *
     * @throws \InvalidArgumentException If configuration is inconsistent
     */
    public function __construct(public readonly bool $hasHeaders = true,
        public readonly ?int                         $firstRowIndex = null,
        public readonly ?int                         $headersRowIndex = null,
        public readonly ?int                         $dataFirstRowIndex = null,
        public readonly ?int                         $dataLastRowIndex = null,
        public readonly bool                         $convertColumnLettersToNumbers = false,
        public readonly bool                         $formulaEvaluation = false,
        public readonly bool                         $formatterEnabled = true,
        public readonly bool                         $skipEmptyRows = true)
    {
        if (
            null !== $this->dataFirstRowIndex
            && null !== $this->dataLastRowIndex
            && $this->dataFirstRowIndex > $this->dataLastRowIndex
        ) {
            throw new \InvalidArgumentException('"dataFirstRowIndex" cannot be greater than "dataLastRowIndex".');
        }
    }

    public function shouldAutoDetectHeaders(): bool
    {
        return true === $this->hasHeaders && null === $this->headersRowIndex;
    }
}
