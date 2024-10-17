<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Excel/SQL Export';
?>

<div class="export-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        Click the button below to export data:
    </p>
    <p>
        <?= Html::a('Export to Excel', ['export'], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
