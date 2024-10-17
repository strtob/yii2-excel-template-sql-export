<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = yii::t('app', 'Exports');
$this->params['breadcrumbs'][] = $this->title;

$search = "$('.search-button').click(function(){
$('.search-form').toggle(1000);
return false;
});";
$this->registerJs($search);

?>
<div id="export-index" class="form-container">


    <?php // echo $this->render('_search', ['model' =>        $searchModel]); 
    ?>


    <div class="card h-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1"><?= \Yii::t('app', 'Overview') ?></h4>
            <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">

                </div>
            </div>
        </div><!-- end card header -->


        <div class="card-body">


            <?php
            $gridColumn = [
                [
                    'attribute' => 'id',
                    'contentOptions' => ['class' => 'id'],
                    'visible' => false,
                ],
                [
                    'attribute' => 'parent_id',
                    'contentOptions' => ['class' => 'parent_id'],
                    'visible' => false,
                ],
                [
                    'attribute' => 'name',

                    'value' => function ($model) {
                        /* @var $model \app\models */

                        return $model->name;
                    },
                ],
                [
                    'attribute' => 'description',

                    'value' => function ($model) {
                        /* @var $model \app\models */

                        return $model->description;
                    },
                ],
                [
                    'attribute' => 'order_by',

                    'value' => function ($model) {
                        /* @var $model \app\models */

                        return $model->order_by;
                    },
                    'headerOptions' => ['style' => 'width: 80px'],

                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['class' => 'actioncolumn', 'style' => 'width:80px'],
                    'template' => '<div style="display: flex;" class="text-end">{delete}</div>',
                    'buttons' => [

                        'delete' => function ($url, $model) {
                            $r = Html::tag('span', '<span class="align-bottom mb-1"></span>', [
                                'class' => 'requestAjaxUrl btn btn-sm btn-soft-danger remove-list',
                                'style' => 'cursor: pointer',
                                'data-title' => \Yii::t('app', 'Delete') . ' #' . $model->id,
                                'data-url' => \yii\helpers\Url::to(['delete'], true),
                                'data-method' => 'GET',
                                'data-parameter' => 'id=' . $model->id,
                                'data-confirmbtnclass' => 'btn-danger',
                                'data-promptMessage' => \Yii::t('app', 'Do you really want to delete entry {id}', ['id' => '#' . $model->id]),
                                'data-pjaxcontainerids' =>
                                '#pjax-container-export',
                            ]);

                            return $r;
                        }
                    ],
                ],
            ];
            ?>

            <?php
            $btCreateTitle = Yii::t(
                'app',
                'Create Export'
            );
            $btCreate = Html::tag(
                'span',
                '<i class="ri-add-line align-bottom me-1"></i> ' . $btCreateTitle,
                [
                    'class' => 'navigateToUrl btn btn-success me-2',
                    'style' => 'cursor: pointer;',
                    'data-url' => \yii\helpers\Url::to(['create'], true),
                ],
            );
           

            ?>

            <?php
            $layout = <<<HTML
            <div class="clearfix">
            </div>
            <div class="toolbar">{toolbar}</div>
            {items}
            <div class="row mt-3">
                <div class="col-6">{pager}</div>
                <div class="col-6 mt-2 align-self-center text-end">{summary}</div>
            </div>
            HTML;
            ?>

            <?= GridView::widget([
                'id' => 'gridview-export',
                'layout' => $layout,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'rowOptions' => function ($model, $key, $index, $grid) {
                    return
                        [
                            'class' => 'navigateToUrl',
                            'style' => 'cursor: pointer;',
                            'data-url' => \yii\helpers\Url::to(['update', 'id' => $model->id], true),
                        ];
                },
                'bordered' => false,
                'condensed' => false,
                'striped' => true,
                'hover' => true,
                'containerOptions' => ['class' => 'table-container-index'],
                'tableOptions' => ['id' => 'offer-table', 'class' => 'table-container-index table dt-responsive nowrap w-100 dataTable no-footer dtr-inline'],
                'options' => ['style' => 'table-layout:fixed;'],
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' =>
                'pjax-container-export']],
                'floatHeader' => true,
                'showFooter' => false,
                'headerContainer' => ['class' => 'tbl-header', 'style' => ''],
                'footerContainer' => ['class' => '', 'style' => ''],
                'headerRowOptions' => ['class' => '', 'style' => 'font-weight:bold;'],
                'footerRowOptions' => ['class' => '', 'style' => 'font-weight:bold;'],
                'export' => false,
                // your toolbar can include the additional full export menu
                'toolbar' => [
                    $btCreate
                ],
            ]); ?>

        </div>
    </div>
</div>