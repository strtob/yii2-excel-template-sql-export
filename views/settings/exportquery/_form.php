<?php

use yii\helpers\Html;
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
            ? ['create-export-query', 'id' => $model->tbl_export_id]
            : ['update-export-query', 'id' => $model->id],
        'options' => [
            'data-pjax-container' => '#pjax-container-export-query',
        ],
    ]);
    ?>

    <div class="export-form">
        <div class="row gy-3 mt-0 mb-4">

            <?= $form->errorSummary($model, ['class' => 'alert alert-danger alert-border-left alert-dismissible']) ?>

            <?= $form->field($model, 'id', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>


            <?= $form->field($model, 'tbl_export_id', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>




            <div class="col-xxl-12 col-md-12 col-sm-12">

                <?= $form->field($model, 'isActive', [
                    'template' => '<label class="control-label">{label}</label><div>{input}</div>{error}{hint}',
                ])->widget(\kartik\checkbox\CheckboxX::classname(), [
                    'pluginOptions' => [
                        'threeState' => false
                    ]
                ]) ?>

            </div>

            <div class="col-xxl-12 col-md-12 col-sm-12">

                <?= $form->field($model, 'sheet_name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

            </div>

            <div class="col-xxl-12 col-md-12 col-sm-12">

                <?= $form->field($model, 'query')->textarea(['rows' => 6])
                 ->label(
                    yii::t('app', 'Query') .' <code>' .  yii::t('app', '(set variables with :variable_name)')  . '</code>'
                ) ?>

            </div>




            <div class="col-xxl-12 col-md-12 col-sm-12">

                <div class="row">


                    <div class="col-xxl-4 col-md-4 col-sm-12">

                        <?= $form->field($model, 'parameter')->textarea(['rows' => 12]) ?>

                        
                    </div>

                    <div class="col-xxl-8 col-md-8 col-sm-12">

                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


                        <?= $form->field($model, 'order_by')->widget(kartik\widgets\RangeInput::classname(), [
                            'options'      => ['placeholder' => '(0 - 100)...'],
                            'html5Options' => ['min' => 0, 'max' => 100],
                            'addon'        => ['append' => ['content' => '<i class="fas fa-list"></i>']]
                        ]) ?>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="form-group mb-2">
        <?= Html::submitButton($model->isNewRecord ?  'Create' :  'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn
            btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// ####################
// load javascript
// ********************
$options                       = [
    'listLoaded' => Yii::t('app', 'Loaded successfully.'),
];

$this->registerJs(
    $this->render(
        '_form.js',
        [
            'position' => \yii\web\View::POS_READY
        ]
    )
);
?>