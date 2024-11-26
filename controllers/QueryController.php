<?php

namespace strtob\yii2ExcelTemplateSqlExport\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use strtob\yii2ExcelTemplateSqlExport\helpers\ExportHelper;
use strtob\yii2ExcelTemplateSqlExport\helpers\QueryHelper;
use strtob\yii2ExcelTemplateSqlExport\helpers\SpreadsheetHelper;

class QueryController extends Controller
{
    /**
     * Exports the result of raw SQL queries as an Excel file.
     *
     * @param string $name The base name for the Excel file, default is 'data'.
     * @param int|null $id Optional ID of a specific query to export.
     * @param int|null $export_id Optional ID to filter queries.
     * @return Response The HTTP response containing the Excel file.
     */
    public function actionExcel($name = 'data', $id = null, $export_id = null)
    {
        $tempFilePath = null; // Initialize temp file path for cleanup

        try {
            // Fetch SQL queries based on the parameters
            $queries = QueryHelper::fetchQueries($id, $export_id);

            if (empty($queries)) {
                return $this->asJson(['success' => false, 'message' => yii::t('app', 'No SQL queries found.')]);
            }

            // Load the spreadsheet from the Export model or create a new one
            $spreadsheet = ExportHelper::loadSpreadsheet($export_id);

            // Add a sheet for each query
            foreach ($queries as $query) {
                SpreadsheetHelper::createSpreadsheet($spreadsheet, $query);
            }

            $spreadsheet->setActiveSheetIndex(0);

            // Save the Excel file to a temporary location
            $tempFilePath = ExportHelper::saveToTempFile($spreadsheet, $name);

            // Use Yii2's sendFile method to send the file to the user
            return Yii::$app->response->sendFile($tempFilePath, basename($tempFilePath))->send();
        } catch (\Exception $e) {
            // Log the error for further analysis (optional)
            Yii::error("Excel export error: " . $e->getMessage(), __METHOD__);

            return $this->asJson([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        } finally {
            // Cleanup temporary file if it exists
            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }


     /**
     * Generates a PDF from the exported Excel file.
     *
     * @param string $name The base name for the Excel file.
     * @param int|null $id Optional ID of a specific query to export.
     * @param int|null $export_id Optional ID to filter queries.
     * @return Response The HTTP response containing the PDF file.
     */
    public function actionPdf($name = 'data', $id = null, $export_id = null)
    {
        $tempFilePath = null; // Initialize temp file path for cleanup

        try {
            // Fetch SQL queries based on the parameters
            $queries = QueryHelper::fetchQueries($id, $export_id);

            if (empty($queries)) {
                return $this->asJson(['success' => false, 'message' => 'No SQL queries found.']);
            }

            // Load the spreadsheet from the Export model or create a new one
            $spreadsheet = ExportHelper::loadSpreadsheet($export_id);

            // Add a sheet for each query
            foreach ($queries as $query) {
                SpreadsheetHelper::createSpreadsheet($spreadsheet, $query);
            }

            $spreadsheet->setActiveSheetIndex(0);

            // Save the Excel file to a temporary location
            $tempFilePath = ExportHelper::saveToTempFile($spreadsheet, $name);

            // Generate PDF from the Excel file
            $pdfFilePath = ExportHelper::generatePdfFromExcel($tempFilePath);

            // Use Yii2's sendFile method to send the PDF file to the user
            return Yii::$app->response->sendFile($pdfFilePath, basename($pdfFilePath))->send();
        } catch (\Exception $e) {
            // Log the error for further analysis (optional)
            Yii::error("PDF export error: " . $e->getMessage(), __METHOD__);

            return $this->asJson([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        } finally {
            // Cleanup temporary files if they exist
            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            if (isset($pdfFilePath) && file_exists($pdfFilePath)) {
                unlink($pdfFilePath);
            }
        }
    }

}
