<?php

use yii\helpers\Html;
use app\models\Mandate;
use yii\widgets\ActiveForm;
use strtob\yii2ExcelTemplateSqlExport\models\Export;

/* generator base: _formrefmanyNested.php */
/* @var $this yii\web\View */
/* @var $model app\models\Export */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="export-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName() . '_ajax',
        'action' =>
        $model->isNewRecord
            ? ['create-export-has-mandate', 'id' => $model->tbl_mandate_id]
            : ['update-export-has-mandate', 'id' => $model->id],
        'options' => [
            'data-pjax-container' => '#pjax-container-export-has-mandate',
        ],
    ]);
    ?>

    <div class="export-form">
        <div class="row gy-3 mt-0 mb-4">



            <?= $form->errorSummary($model, ['class' => 'alert alert-danger alert-border-left alert-dismissible']) ?>
            <?= $form->field($model, 'id', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>

            <?= $form->field($model, 'tbl_export_id', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>



            <div class="col-xxl-12 col-md-12 col-sm-12">

                <?= $form->field($model, 'tbl_mandate_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(Mandate::find()->orderBy('id')->all(), 'id', 'tblCompany.name'),
                    'options' => ['placeholder' => 'Choose...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>

            </div>

            <div class="col-xxl-6 col-md-6 col-sm-12">

                <?= $form->field($model, 'valid_from')->widget(\kartik\datecontrol\DateControl::classname(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                    'saveFormat' => 'php:Y-m-d H:i:s',
                    'ajaxConversion' => true,
                    'options' => [
                        'pluginOptions' => [
                            'placeholder' => 'Choose...',
                            'autoclose' => true,
                        ]
                    ],
                ]); ?>

            </div>

            <div class="col-xxl-6 col-md-6 col-sm-12">

                <?= $form->field($model, 'valid_until')->widget(\kartik\datecontrol\DateControl::classname(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                    'saveFormat' => 'php:Y-m-d H:i:s',
                    'ajaxConversion' => true,
                    'options' => [
                        'pluginOptions' => [
                            'placeholder' => 'Choose...',
                            'autoclose' => true,
                        ]
                    ],
                ]); ?>

            </div>


        </div>
    </div>

    <div class="form-group mb-2">
        <?= Html::submitButton($model->isNewRecord ? yii::t('app',  'Create') : yii::t('app',  'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn
            btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>