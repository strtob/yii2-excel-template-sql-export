<?php

namespace strtob\yii2ExcelTemplateSqlExport\helpers;

use strtob\yii2ExcelTemplateSqlExport\models\ExportSqlQuery;
use Yii;

class QueryHelper
{
    /**
     * Fetches SQL queries based on the given parameters.
     *
     * @param int|null $id Optional ID to filter a specific query.
     * @param int|null $tbl_export_id Optional ID to filter queries by export table ID.
     * @return ExportSqlQuery[] List of query models matching the criteria.
     */
    public static function fetchQueries($id = null, $tbl_export_id = null)
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
     * Executes the given SQL query and returns the result.
     *
     * @param string $sql The SQL query to execute.
     * @param array|null $parameter Array of parameters to bind to the query.
     * @return array The query result set.
     * @throws \InvalidArgumentException if $parameter is not an array.
     */
    public static function executeQuery($sql, $parameter)
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
}
