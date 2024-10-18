<?php
namespace strtob\yii2ExcelTemplateSqlExport\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Response;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQuery;

class QueryController extends Controller
{
    /**
     * Export the result of raw SQL queries as an Excel file.
     *
     * @param int $id Optional ID of a specific query to export.
     * @param int|null $tbl_export_id Optional ID to filter queries.
     * @return Response
     */
    public function actionExcel($name = 'data', $id = null, $export_id = null)
    {
        try {
            // Fetch SQL queries based on the parameters
            $queries = $this->fetchQueries($id, $export_id);

            if (empty($queries)) {
                return $this->asJson(['success' => false, 'message' => 'No SQL queries found.']);
            }

            // Create a single spreadsheet object for all queries
            $spreadsheet = new Spreadsheet();

            // Remove the default sheet created
            $spreadsheet->removeSheetByIndex(0);

            // Add a sheet for each query
            foreach ($queries as $query) {
                $this->createSpreadsheet($spreadsheet, $query);
            }

            // Save the Excel file to a temporary location
            $tempFilePath = $this->saveToTempFile($spreadsheet, $name);

            // Use Yii2's sendFile method to send the file to the user
            return Yii::$app->response->sendFile($tempFilePath, basename($tempFilePath))->send();
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return $this->asJson([
                    'success' => false, 
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
        }
    }

    /**
     * Fetches SQL queries based on the given parameters.
     *
     * @param int|null $id
     * @param int|null $tbl_export_id
     * @return ExportSqlQuery[]
     */
    protected function fetchQueries($id = null, $tbl_export_id = null)
    {
        //TODO: tbl_mandate_id is currently not used in the query
        $query = ExportSqlQuery::find();

        if ($id !== null) {
            $query->andWhere(['id' => $id]);
        }

        if ($tbl_export_id !== null) {
            $query->andWhere(['tbl_export_id' => $tbl_export_id]);
        }

        return $query->all(); // Return the list of queries
    }

    /**
     * Create a sheet within the spreadsheet for the query result
     *
     * @param Spreadsheet $spreadsheet The main spreadsheet object.
     * @param ExportSqlQuery $query The SQL query model.
     */
    protected function createSpreadsheet($spreadsheet, $query)
    {
        // Create a new worksheet for each query, and use the query's sheet_name as the worksheet name
        $sheetName = $query->sheet_name ?? 'Sheet'; // Fallback to 'SheetN' if sheet_name is empty
        $worksheet = $spreadsheet->createSheet(); // Create a new worksheet
        $worksheet->setTitle($sheetName); // Set the name of the sheet

        $sql = $query->query;
        $parameter = json_decode($query->parameter, true);

        
        if (!is_null($parameter) && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }

        $data = $this->executeQuery($sql, $parameter);

        if (!empty($data)) {
            // Write column titles in the first row
            $col = 'A'; // Start with column 'A'
            foreach ($data[0] as $key => $val) {
                $worksheet->setCellValue($col . '1', $key); // Write column title into first row
                $col++; // Move to the next column
            }

            // Write the data starting from the second row
            for ($i = 0; $i < count($data); $i++) {
                $col = 'A'; // Reset to column 'A' for each row
                $row = 2 + $i; // Data starts from row 2
                foreach ($data[$i] as $val) {
                    $worksheet->setCellValue($col . $row, $val); // Write data into the appropriate cell
                    $col++; // Move to the next column
                }
            }
        }
    }

    /**
     * Executes the given SQL query and returns the result.
     *
     * @param string $sql
     * @return array
     */
    protected function executeQuery($sql, $parameter)
    {
        
        // Create a command and bind parameters
        $command = Yii::$app->db->createCommand($sql);
    
        // Check if $parameter is an array
        if (is_array($parameter)) {
            foreach ($parameter as $key => $paramDetails) {
                // Check if the current paramDetails contains 'parameter' and 'example'
                if (isset($paramDetails['parameter']) && isset($paramDetails['example'])) {
                    // Bind only if the parameter is found in the SQL string
                    if (strpos($sql, $paramDetails['parameter']) !== false) {
                        $command->bindValue($paramDetails['parameter'], $paramDetails['example']);
                    }
                }
            }
        } 
        elseif (!is_null($parameter)) {
            // Handle the case where $parameter is not an array
            throw new \InvalidArgumentException('Expected parameter to be an array, ' . gettype($parameter) . ' given.');
        }
    
        // Execute the command and return the results
        return $command->queryAll();
    }
    
    

    /**
     * Saves the Spreadsheet object to a temporary file and returns the file path.
     *
     * @param Spreadsheet $spreadsheet
     * @return string
     */
    protected function saveToTempFile($spreadsheet, $name)
    {
        $filename = date('Y-m-d_H-i-s') . '_' .$name . '.xlsx';
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename; // Path to save the temporary file

        // Write the Excel file to the temporary location
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath); // Save to temp directory

        return $tempFilePath;
    }
}
