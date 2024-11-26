<?php

namespace strtob\yii2ExcelTemplateSqlExport\helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SpreadsheetHelper
{
    /**
     * Creates a new sheet within the spreadsheet for each query result.
     *
     * @param Spreadsheet $spreadsheet The main spreadsheet object.
     * @param ExportSqlQuery $query The SQL query model with the sheet data.
     * @throws \Exception if there is an issue with JSON decoding.
     */
    public static function createSpreadsheet(Spreadsheet $spreadsheet, $query)
    {
        // Create a new worksheet for each query, and use the query's sheet_name as the worksheet name
        $sheetName = $query->sheet_name ?? 'Sheet';
        $worksheet = $spreadsheet->createSheet();
        $worksheet->setTitle($sheetName);

        $sql = $query->query;
        $parameter = json_decode($query->parameter, true);

        if (!is_null($parameter) && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }

        $data = QueryHelper::executeQuery($sql, $parameter);

        if (!empty($data)) {
            // Set the formula for incrementing numbers in the first row (A1, B1, C1, etc.)
            $col = 'A';
            $worksheet->setCellValue('A1', 1);
            $previousCol = 'A';

            for ($i = 1; $i < count($data[0]); $i++) {
                $col = chr(ord($previousCol) + 1);
                $worksheet->setCellValueExplicit(
                    $col . '1',
                    "=$previousCol" . "1+1",
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_FORMULA
                );
                $previousCol = $col;
            }

            // Write column titles in the second row
            $col = 'A';
            foreach ($data[0] as $key => $val) {
                $worksheet->setCellValue($col . '2', $key);
                $col++;
            }

            // Write the data starting from the third row
            for ($i = 0; $i < count($data); $i++) {
                $col = 'A';
                $row = 3 + $i;
                foreach ($data[$i] as $val) {
                    $worksheet->setCellValue($col . $row, $val);
                    $col++;
                }
            }

            // Format headers
            $highestColumn = $worksheet->getHighestColumn();
            $headerSerialRange = 'A1:' . $highestColumn . '1';
            $headerRange = 'A2:' . $highestColumn . '2';

            foreach (range('A', $highestColumn) as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }

            $worksheet->getRowDimension(2)->setRowHeight(25);

            $worksheet->getStyle($headerSerialRange)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $worksheet->getStyle($headerRange)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $worksheet->getStyle($headerRange)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('eeeeee');

            $worksheet->getStyle($headerRange)->getFont()->setBold(true);

            $worksheet->getStyle($headerRange)->getBorders()->getBottom()
                ->setBorderStyle(Border::BORDER_THIN);

            $worksheet->freezePane('A3');
            $worksheet->setAutoFilter($headerRange);
        }
    }
}
