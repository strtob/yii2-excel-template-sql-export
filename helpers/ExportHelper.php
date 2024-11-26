<?php

namespace strtob\yii2ExcelTemplateSqlExport\helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\models\Export;
use Yii;

class ExportHelper
{
    /**
     * Load the spreadsheet from the Export model or create a new one.
     *
     * @param int|null $exportId
     * @return Spreadsheet
     */
    public static function loadSpreadsheet($exportId = null)
    {
        if ($exportId !== null) {
            $exportModel = Export::findOne($exportId);
            if (!$exportModel) {
                throw new \Exception('Export not found for the provided export_id.');
            }

            // Load the template file if it exists
            if (isset($exportModel->templateTblFile) && $exportModel->templateTblFile) {
                $filePath = $exportModel->templateTblFile->createTempFile();
                return IOFactory::load($filePath);
            }
        }

        // Create a new empty Spreadsheet if no exportId or no template found
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        return $spreadsheet;
    }

    /**
     * Saves the Spreadsheet object to a temporary file and returns the file path.
     *
     * @param Spreadsheet $spreadsheet The spreadsheet object to save.
     * @param string $name The base name for the Excel file.
     * @return string The temporary file path where the Excel file is saved.
     */
    public static function saveToTempFile($spreadsheet, $name)
    {
        $filename = date('Y-m-d_H-i-s') . '_' . $name . '.xlsx';
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        return $tempFilePath;
    }

    /**
     * Generates a PDF from the provided Excel file using headless LibreOffice.
     *
     * @param string $excelFilePath The path to the Excel file.
     * @return string The path to the generated PDF file.
     * @throws \Exception If PDF generation fails.
     */
    public static function generatePdfFromExcel($excelFilePath)
    {
        $pdfFilePath = str_replace('.xlsx', '.pdf', $excelFilePath);
        $command = "libreoffice --headless --convert-to pdf --outdir " . escapeshellarg(dirname($pdfFilePath)) . " " . escapeshellarg($excelFilePath);

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('PDF generation failed: ' . implode("\n", $output));
        }

        return $pdfFilePath;
    }
}
