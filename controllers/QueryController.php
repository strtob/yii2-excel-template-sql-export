<?php

namespace strtob\yii2ExcelTemplateSqlExport\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Response;
use app\models\Export;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQuery;

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
            $queries = $this->fetchQueries($id, $export_id);

            if (empty($queries)) {
                return $this->asJson(['success' => false, 'message' => 'No SQL queries found.']);
            }



            // Check if export_id is provided, attempt to load the Export model and template
            if ($export_id !== null) {
                $exportModel = Export::findOne(['id' => $export_id]);
                if (!$exportModel) {
                    return $this->asJson([
                        'success' => false,
                        'message' => 'Export not found for the provided export_id.',
                    ]);
                }

                // Load the template file if it exists, otherwise use an empty spreadsheet
                if (isset($exportModel->templateTblFile) && $exportModel->templateTblFile) {
                    $filePath = $exportModel->templateTblFile->createTempFile();
                    $spreadsheet = IOFactory::load($filePath);
                } else {
                    // No template file available, create a new empty Spreadsheet
                    $spreadsheet = new Spreadsheet();
                    $spreadsheet->removeSheetByIndex(0);
                }
            } else {
                // If no export_id provided, create a new Spreadsheet without any template
                $spreadsheet = new Spreadsheet();
                $spreadsheet->removeSheetByIndex(0);
            }


            // Add a sheet for each query
            foreach ($queries as $query) {
                $this->createSpreadsheet($spreadsheet, $query);
            }

            $spreadsheet->setActiveSheetIndex(0);

            // Save the Excel file to a temporary location
            $tempFilePath = $this->saveToTempFile($spreadsheet, $name);

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
     * Fetches SQL queries based on the given parameters.
     *
     * @param int|null $id Optional ID to filter a specific query.
     * @param int|null $tbl_export_id Optional ID to filter queries by export table ID.
     * @return ExportSqlQuery[] List of query models matching the criteria.
     */
    protected function fetchQueries($id = null, $tbl_export_id = null)
    {
        $query = ExportSqlQuery::find();

        if ($id !== null) {
            $query->andWhere(['id' => $id]);
        }

        if ($tbl_export_id !== null) {
            $query->andWhere(['tbl_export_id' => $tbl_export_id]);
        }

        return $query->all();
    }

    /**
     * Creates a new sheet within the spreadsheet for each query result.
     *
     * @param Spreadsheet $spreadsheet The main spreadsheet object.
     * @param ExportSqlQuery $query The SQL query model with the sheet data.
     * @throws \Exception if there is an issue with JSON decoding.
     */
    protected function createSpreadsheet($spreadsheet, $query)
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

        $data = $this->executeQuery($sql, $parameter);

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
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $worksheet->getStyle($headerRange)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $worksheet->getStyle($headerRange)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('eeeeee');

            $worksheet->getStyle($headerRange)->getFont()->setBold(true);

            $worksheet->getStyle($headerRange)->getBorders()->getBottom()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $worksheet->freezePane('A3');
            $worksheet->setAutoFilter($headerRange);
        }
    }

    /**
     * Executes the given SQL query and returns the result.
     *
     * @param string $sql The SQL query to execute.
     * @param array|null $parameter Array of parameters to bind to the query.
     * @return array The query result set.
     * @throws \InvalidArgumentException if $parameter is not an array.
     */
    protected function executeQuery($sql, $parameter)
    {
        $command = Yii::$app->db->createCommand($sql);

        if (is_array($parameter)) {
            foreach ($parameter as $key => $paramDetails) {
                if (isset($paramDetails['parameter']) && isset($paramDetails['example'])) {
                    if (strpos($sql, $paramDetails['parameter']) !== false) {
                        $command->bindValue($paramDetails['parameter'], $paramDetails['example']);
                    }
                }
            }
        } elseif (!is_null($parameter)) {
            throw new \InvalidArgumentException('Expected parameter to be an array, ' . gettype($parameter) . ' given.');
        }

        return $command->queryAll();
    }

    /**
     * Saves the Spreadsheet object to a temporary file and returns the file path.
     *
     * @param Spreadsheet $spreadsheet The spreadsheet object to save.
     * @param string $name The base name for the Excel file.
     * @return string The temporary file path where the Excel file is saved.
     */
    protected function saveToTempFile($spreadsheet, $name)
    {
        $filename = date('Y-m-d_H-i-s') . '_' . $name . '.xlsx';
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        return $tempFilePath;
    }
}
