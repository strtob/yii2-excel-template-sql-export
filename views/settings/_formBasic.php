<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use strtob\yii2Filemanager\models\File;

/* generator base: _formBasic.php */
/* @var $this yii\web\View */
/* @var $model app\models\Export */
/* @var $form yii\widgets\ActiveForm */

?>

<div id="export-basic-form" class='main-form'>

    <div class="d-flex align-items-center mb-3">
        <h5 class="flex-grow-1 fs-16 mb-0 text-primary">
            <i class="fa-solid fa-home me-1"></i> <span class="form-title"><?= Yii::t('app', 'Basic') ?></span>

        </h5>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => $model->formName() . ($model->isNewRecord ? '' : '_ajax'),
        'action' =>
        $model->isNewRecord ? ['create', 'id' => $model->id] : ['update', 'id' => $model->id],
    ]);
    ?>

    <?= $form->errorSummary($model); ?>

    <div id="export-basic-form-fields"
        class="row gy-3 mt-0 mb-4 basic-form">


        <?= $form->field($model, 'id', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>

        <?= $form->field($model, 'parent_id', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>

        <div class="col-xxl-6 col-md-6 col-sm-12">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

        </div>

        <div class="col-xxl-6 col-md-6 col-sm-12">

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        </div>


        <div class="col-xxl-6 col-md-6 col-sm-12">

            <?= $form->field($model, 'template_tbl_file_id')
                ->widget(\strtob\yii2Filemanager\widgets\FileSelectionInputWidget::class, [
                    'prependContent' => '<i class="fa-regular fa-file-excel me-2"></i>'
                        . 'MS Excel',
                ]) ?>


        </div>

        <div class="col-xxl-6 col-md-6 col-sm-12">


            <?= $form->field($model, 'presentation_tbl_file_id')
                ->widget(\strtob\yii2Filemanager\widgets\FileSelectionInputWidget::class, [
                    'prependContent' => '<i class="fa-regular fa-file-powerpoint me-2"></i>'
                        . 'MS Powerpoint'
                ]) ?>

        </div>

        <div class="col-xxl-6 col-md-6 col-sm-12">

            <?= $form->field($model, 'order_by')->widget(kartik\widgets\RangeInput::classname(), [
                'options'      => ['placeholder' => '(0 - 100)...'],
                'html5Options' => ['min' => 0, 'max' => 100],
                'addon'        => ['append' => ['content' => '<i class="fas fa-list"></i>']]
            ]) ?>

        </div>


    </div>
    <div class="form-group mb-2">
        <?= Html::submitButton($model->isNewRecord
            ? 'Create'
            : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn
    btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>