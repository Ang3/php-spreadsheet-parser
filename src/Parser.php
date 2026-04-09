<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/php-spreadsheet-parser
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Component\Spreadsheet;

use Ang3\Component\Spreadsheet\Formatter\CellValueFormatterInterface;
use Ang3\Component\Spreadsheet\Formatter\DefaultCellValueFormatter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

/**
 * @author Joanis ROUANET
 */
class Parser
{
    private CellValueFormatterInterface $formatter;

    public function __construct(?CellValueFormatterInterface $formatter = null)
    {
        $this->formatter = $formatter ?? new DefaultCellValueFormatter();
    }

    /**
     * @param string $file The filename to load
     *
     * @return array<string, array<int, mixed>>
     */
    public function read(string $file, Context $context): array
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(\sprintf('File "%s" does not exist.', $file));
        }

        $parser = $context->format->getParser();

        try {
            $spreadsheet = $parser->load($file);
        } catch (\Exception $e) {
            throw new \RuntimeException(\sprintf('Excel decoding failed - %s', $e->getMessage()), 0, $e);
        }

        $options = $context->options;
        $loadedSheetNames = $spreadsheet->getSheetNames();
        $data = [];

        foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $data[$loadedSheetName] = [];
            $headers = null;

            foreach ($worksheet->getRowIterator() as $row) {
                if ($options->firstRowIndex && $row->getRowIndex() < $options->firstRowIndex) {
                    continue;
                }

                if (null === $headers && $options->hasHeaders) {
                    $headers = $this->resolveHeaders($row, $options);

                    continue;
                }

                if ($options->skipEmptyRows && $row->isEmpty()) {
                    continue;
                }

                if ($options->dataStartRowIndex && $row->getRowIndex() < $options->dataStartRowIndex) {
                    continue;
                }

                if ($options->dataEndRowIndex && $row->getRowIndex() > $options->dataEndRowIndex) {
                    break;
                }

                foreach ($row->getCellIterator() as $cell) {
                    $cellValue = $cell->getValueString();

                    if ($options->formulaEvaluation && $cell->isFormula()) {
                        $cellValue = $cell->getCalculatedValue();
                    } elseif ($options->formatterEnabled) {
                        $cellValue = $this->formatter->extract($cell, $context);
                    }

                    if ($options->hasHeaders && $headers) {
                        $columnIndex = $headers[$cell->getColumn()];
                    } else {
                        $columnIndex = $cell->getColumn();

                        if ($options->convertColumnLettersToNumbers) {
                            $columnIndex = Coordinate::columnIndexFromString($columnIndex);
                        }
                    }

                    $data[$loadedSheetName][$row->getRowIndex()][$columnIndex] = $cellValue;
                }
            }
        }

        return $data;
    }

    /**
     * @return array<string, string>|null
     */
    private function resolveHeaders(Row $row, Options $options): ?array
    {
        $headers = [];

        if ($options->headersRowIndex) {
            if ($row->getRowIndex() !== $options->headersRowIndex) {
                return null;
            }
        } else {
            if ($row->isEmpty()) {
                return null;
            }
        }

        foreach ($row->getCellIterator() as $cell) {
            $headers[$cell->getColumn()] = $cell->getValueString();
        }

        return $headers;
    }
}
