<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-export-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}','options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'parent_id', ['template' => '{input}','options' => ['tag' => false]])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'order_by')->widget(kartik\widgets\RangeInput::classname(), [
                'options'      => ['placeholder' => '(0 - 100)...'],
                'html5Options' => ['min' => 0, 'max' => 100],
                'addon'        => ['append' => ['content' => '<i class="fas fa-list"></i>']]
            ]) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass"></i>' . 'Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
