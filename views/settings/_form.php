<?php
/* base: _form.php */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* generator base: _form.php */
/* @var $this yii\web\View */
/* @var $model app\models\Export */
/* @var $form yii\widgets\ActiveForm */



?>

<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1"><?= \Yii::t('app', 'Details') ?></h4>
        <div class="flex-shrink-0">
            <div class="form-check form-switch form-switch-right form-switch-md">
            </div>
        </div>
    </div><!-- end card header -->

    <div class="card-body pt-0">
        <div class="export-form main-form">

            <?=
            \strtob\yii2WidgetToolkit\formTab\FormTabWidget::widget([
            'model' => $model,
            'dataProviders' => $dataProviders,
            'searchModels' => $searchModels,
                        'tbl_sys_link_table_id' => 12, // see: tbl_sys_file_has_link_table
            'linked_table_id' => $model->id,
                        'tabItems' => [
            [
            'label' => \Yii::t('app', 'Basic'),
            'iconCss' => strtob\yii2helpers\IconHelper::get('Basic'),
            'view' => Yii::$app->controller->viewPath . '/_formBasic',
            'param' => [
            'model' => $model,
            'dataProviders' => $dataProviders,
            'searchModels' => $searchModels,
            ],
            'dataproviderKey' => null,
            'showIfNewRecord' => true,
            ],
                                [
                    'label' => \Yii::t('app', 'Mandate'),
                    'iconCss' => strtob\yii2helpers\IconHelper::get('mandate'),
                    'view' => Yii::$app->controller->viewPath . '/exporthasmandate/_index',
                    'param' => [
                    'model' => $model,
                    'dataProviders' => $dataProviders,
                    'searchModels' => $searchModels,
                    ],
                    'dataproviderKey' => null,
                    ],

                                        [
                    'label' => \Yii::t('app', 'Query'),
                    'iconCss' => strtob\yii2helpers\IconHelper::get('query'),
                    'view' => Yii::$app->controller->viewPath . '/exportquery/_index',
                    'param' => [
                    'model' => $model,
                    'dataProviders' => $dataProviders,
                    'searchModels' => $searchModels,
                    ],
                    'dataproviderKey' => null,
                    ],

                    
            ]
            ])
            ?>

        </div>
    </div>