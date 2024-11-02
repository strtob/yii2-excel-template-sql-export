<?php

namespace strtob\yii2ExcelTemplateSqlExport\controllers;

use yii\web\Controller;
use Yii;

class DefaultController extends Controller
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

   
}
