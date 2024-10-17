<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExportHasMandate */

$this->title = 'Create Export';
$this->params['breadcrumbs'][] = ['label' => 'Exports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="export-create">

    <?= $this->render('_form', [
        'model' => $model,
            ]) ?>

</div>

