<?php

use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* generator base: _formrefmany.php */

?>

<?php
\strtob\yii2WidgetToolkit\formTab\Helper::tabCounterMarker(
        'pjax-container-export-query',
        $dataProviders['ExportQuery']
)
?>

<div id="form-export-query" class='main-form'>


        <div class="d-flex align-items-center mb-3">
                <h5 class="flex-grow-1 fs-16 mb-0 text-primary">
                        <i class="fa-solid fa-database me-1"></i> <span class="form-title"><?= Yii::t('app', 'Query') ?></span>
                </h5>
        </div>

        <?php
        $gridColumn = [
                [
                        'attribute' => 'id',
                        'contentOptions' => ['class' => 'id'],
                        'visible' => false,
                ],
                [
                        'attribute' => 'isActive',
                        'class' => 'kartik\grid\BooleanColumn',
                        'width' => '10px',
                        //'label' => Yii::t('yii', 'seen'),
                        'hAlign' => 'center',
                        //'filterType' => GridView::FILTER_CHECKBOX_X,
                        'filterOptions' => ['class' => 'kv-align-center'],
                ],
                [
                        'attribute' => 'sheet_name',

                        'value' => function ($model) {
                                /* @var $model \app\models */

                                return $model->sheet_name;
                        },
                ],
                [
                        'attribute' => 'query',
                        'value' => function ($model) {
                                /* @var $model \app\models */
                                return $model->query;
                        },
                ],
                [
                        'attribute' => 'parameter',
                        'value' => function ($model) {
                                /* @var $model \app\models */
                                return $model->parameter;
                        },
                ],
                [
                        'attribute' => 'btExecuteQuery',
                        'label' => '',
                        'format' => 'raw',
                        'contentOptions' => ['class' => 'actioncolumn'],
                        'value' => function ($model) {
                                /* @var $model \app\models */

                                $btExecuteQueryTitle = Yii::t('app', 'Execute query');
                                return $btExecuteQuery = Html::tag(
                                        'span',
                                        '<i class="fas fa-database me-1"></i> ' . $btExecuteQueryTitle,
                                        [
                                                'class' => 'btn btn-primary executeExportQuery none-icon',
                                                'style' => 'cursor: pointer; align-items: center;',
                                                'data-url' => \yii\helpers\Url::to(['/export/query/excel', 'id' => $model->id], true),
                                                'data-name' => $model->tblExport->name,
                                        ]
                                );
                        },
                ],
                // [
                //         'attribute' => 'description',
                //         'value' => function ($model) {
                //                 /* @var $model \app\models */
                //                 return $model->description;
                //         },
                // ],
                [
                        'attribute' => 'order_by',
                        'label' => Yii::t('app', 'Order'),
                        'value' => function ($model) {
                                /* @var $model \app\models */
                                return $model->order_by;
                        },
                        'headerOptions' => ['style' => 'width: 80px'],
                ],
                [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class' => 'actioncolumn', 'style' => 'width:80px'],
                        'template' => '<div style="display: flex;">{delete}</div>',
                        'buttons' => [
                                'delete' => function ($url, $model) {
                                        $r = Html::tag('span', '<span class="mb-1 align-bottom"></span>', [
                                                'class' => 'requestAjaxUrl btn btn-sm btn-danger remove-list',
                                                'style' => 'cursor: pointer',
                                                'data-title' => \Yii::t('app', 'Delete') . ' #' . $model->id,
                                                'data-url' => \yii\helpers\Url::to(['delete-export-query'], true),
                                                'data-method' => 'GET',
                                                'data-parameter' => 'id=' . $model->id,
                                                'data-confirmbtnclass' => 'btn-danger',
                                                'data-promptMessage' => \Yii::t('app', 'Do you really want to delete entry {id}', ['id' => ' #' . $model->id]),
                                                'data-pjaxcontainerids' => '#pjax-container-export-query',
                                        ]);
                                        return $r;
                                }
                        ],
                ],
        ];
        ?>

        <?php

        $btCreateTitle = Yii::t('app', 'Add Query');
        $btCreate = Html::tag(
                'span',
                $btCreateTitle,
                [
                        'title' => $btCreateTitle,
                        'class' => 'showModal btn btn-success my-0 mr-2',
                        'style' => 'cursor: pointer;',
                        'title' => Yii::t('app', 'Add Entry') . ' #' . $model->id,
                        'data-url' => \yii\helpers\Url::to(['create-export-query', 'id' => $model->id], true),
                        'data-bs-target' => 'modal-xl',
                        'data-target-title' => '<i class="ri-pencil-fill align-bottom"></i> ' . Yii::t('app', 'Create'),
                        'data-ajaxcontainer' => '#pjax-container-export-query',                       
                ],
        );

        $btExecuteQueryTitle = Yii::t('app', 'Execute all queries');
        $btExecuteQuery = Html::tag(
                'span',
                '<i class="fas fa-database me-1"></i> ' . $btExecuteQueryTitle,
                [
                        'title' => $btExecuteQueryTitle,
                        'class' => 'btn btn-primary my-0 mr-2 none-icon executeExportQuery',
                        'style' => 'cursor: pointer;',
                        // Adjusted to include tbl_export_id
                        'data-url' => \yii\helpers\Url::to(['/export/query/excel', 'export_id' => $model->id], true),
                        'data-name' => $model->name,
                ]
        );

        $btGeneratePdfTitle = Yii::t('app', 'Generate PDF');
        $btGeneratePdf = Html::tag(
                'span',
                '<i class="fa-regular fa-file-pdf me-1"></i> ' . $btGeneratePdfTitle,
                [
                        'title' => $btGeneratePdfTitle,
                        'class' => 'btn btn-danger my-0 mr-2 none-icon executeExportQuery',
                        'style' => 'cursor: pointer;',
                        // Adjusted to include tbl_export_id
                        'data-url' => \yii\helpers\Url::to(['/export/query/pdf', 'export_id' => $model->id], true),
                        'data-name' => $model->name,
                ]
        );




        $label = 'Query';

        $layout = '<div class="clearfix">
</div>
<div class="toolbar">{toolbar}</div>
{items}
<div class="pagination-wrap">{pager}</div>';

        ?>

        <?= GridView::widget([
                'id' => 'gridview-export-query',
                'layout' => $layout,
                'dataProvider' => isset($dataProviders['ExportQuery']) ? $dataProviders['ExportQuery'] : null,
                'filterModel' => isset($searchModels['ExportQuery']) ? $searchModels['ExportQuery'] : null,
                'columns' => $gridColumn,
                'rowOptions' => function ($model, $key, $index, $grid) {
                        return
                                [
                                        'class' => 'showModal',
                                        'style' => 'cursor: pointer;',
                                        'title' => Yii::t('app', 'Update Entry') . ' #' . $model->id,
                                        'data-url' => \yii\helpers\Url::to(['update-export-query', 'id' => $model->id], true),
                                        'data-bs-target' => 'modal-xl',
                                        'data-target-title' => '<i class="ri-pencil-fill align-bottom"></i> ' . Yii::t('app', 'Update'),
                                        'data-ajaxcontainer' => '#pjax-container-export-query',
                                ];
                },
                'floatHeader' => true,
                'floatHeaderOptions' => ['scrollingTop' => '50'],
                'bordered' => false,
                'condensed' => false,
                'striped' => true,
                'hover' => true,
                'tableOptions' => ['id' => 'table-export-query', 'class' => 'table table-form dt-responsive nowrap w-100 dataTable no-footer dtr-inline'],
                'options' => ['style' => 'table-layout:fixed'],
                'containerOptions' => ['style' => '', 'class' => 'table-container-form'],
                'headerContainer' => ['class' => 'tbl-header', 'style' => ''],
                'footerContainer' => ['class' => '', 'style' => ''],
                'headerRowOptions' => ['class' => '', 'style' => 'font-weight:bold;'],
                'footerRowOptions' => ['class' => '', 'style' => 'font-weight:bold;'],
                'showFooter' => false,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'pjax-container-export-query']],
                'toolbar' => [$btCreate, $btExecuteQuery, $btGeneratePdf],
        ]); ?>

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
                '_script.js',
                [
                        'position' => \yii\web\View::POS_READY
                ]
        )
);
?>