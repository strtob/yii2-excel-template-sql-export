<?php

namespace strtob\yii2ExcelTemplateSqlExport\controllers;

use yii\web\Controller;
use Yii;

class SettingsController extends Controller
{
    /**
     * Renders the export page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Example action for exporting data to Excel or running SQL export.
     */
    public function actionExport()
    {
        // You would place the actual export logic here.
        return Yii::$app->response->sendContentAsFile("Your export data", "export.xlsx");
    }
}
