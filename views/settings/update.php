<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Export */

$this->title =
'Update Export: '. ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' =>
'Exports',
'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' =>
['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="export-update">

    <?=  $this->render('_form', [
    'model' => $model,
            'dataProviders' => $dataProviders,
        'searchModels' => $searchModels,
        ]) ?>

</div>