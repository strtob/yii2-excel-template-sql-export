<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use strtob\yii2ExcelTemplateSqlExport\models\Export;

/* generator base: _formrefmany.php */

?>

<?php
\strtob\yii2WidgetToolkit\formTab\Helper::tabCounterMarker(
    'pjax-container-export-has-mandate',
    $dataProviders['ExportHasMandate']
)
?>

<div id="form-export-has-mandate" class='main-form'>


    <div class="d-flex align-items-center mb-3">
        <h5 class="flex-grow-1 fs-16 mb-0 text-primary">
            <i class="fa-solid fa-book me-1"></i> <span class="form-title"><?= Yii::t('app', 'Mandate') ?></span>
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
            'attribute' => 'status',                
            'label' => yii::t('app', 'Status'), 
            'format' => 'raw',
            'value' => function ($model) {
                $v = $model->validityStage;

                $r = '<div class="d-flex"><div>';
                $r .= '<i class="' . $v->icon . ' me-2"></i>';
                $r .= '</div>';
                $r .= '<div>';
                $r .= $v->message;
                $r .= '<div><small>' . $v->relative_time . '</small></div>';
                $r .= '</div></div>';
                return $r;
            },
        ],

        [
            'attribute' => 'mandate',
            'label' => yii::t('app',  'Mandate'),
          
            'value' => function ($model) {
                /* @var $model app\models */
                return $model->tblMandate->tblCompany->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(Export::find()->all(), 'id', 'tblMandate.name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '(Filter)', 'id' => 'grid-export-search-tbl_export_id']
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
                        'data-url' => \yii\helpers\Url::to(['delete-export-has-mandate'], true),
                        'data-method' => 'GET',
                        'data-parameter' => 'id=' . $model->id,
                        'data-confirmbtnclass' => 'btn-danger',
                        'data-promptMessage' => \Yii::t('app', 'Do you really want to delete entry {id}', ['id' => ' #' . $model->id]),
                        'data-pjaxcontainerids' => '#pjax-container-export-has-mandate',
                    ]);
                    return $r;
                }
            ],
        ],
    ];
    ?>

    <?php

    $btCreateTitle = Yii::t('app', 'Add Mandate');
    $btCreate = Html::tag(
        'span',
        '<i class="ri-add-line align-bottom me-1"></i> ' . $btCreateTitle,
        [
            'title' => $btCreateTitle,
            'class' => 'showModal btn btn-success my-0 mr-2',
            'style' => 'cursor: pointer;',
            'title' => Yii::t('app', 'Add Entry') . ' #' . $model->id,
            'data-url' => \yii\helpers\Url::to(['create-export-has-mandate', 'id' => $model->id], true),
            'data-bs-target' => 'modal-xl',
            'data-target-title' => '<i class="ri-pencil-fill align-bottom"></i> ' . Yii::t('app', 'Create'),
            'data-ajaxcontainer' => '#pjax-container-export-has-mandate',
        ],
    );



    $label = 'Has Mandate';

    $layout = '<div class="clearfix">
</div>
<div class="toolbar">{toolbar}</div>
{items}
<div class="pagination-wrap">{pager}</div>';

    ?>

    <?= GridView::widget([
        'id' => 'gridview-export-has-mandate',
        'layout' => $layout,
        'dataProvider' => isset($dataProviders['ExportHasMandate']) ? $dataProviders['ExportHasMandate'] : null,
        'filterModel' => isset($searchModels['ExportHasMandate']) ? $searchModels['ExportHasMandate'] : null,
        'columns' => $gridColumn,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return
                [
                    'class' => 'showModal',
                    'style' => 'cursor: pointer;',
                    'title' => Yii::t('app', 'Update Entry') . ' #' . $model->id,
                    'data-url' => \yii\helpers\Url::to(['update-export-has-mandate', 'id' => $model->id], true),
                    'data-bs-target' => '#modal-xl',
                    'data-target-title' => '<i class="ri-pencil-fill align-bottom"></i> ' . Yii::t('app', 'Update'),
                    'data-ajaxcontainer' => '#pjax-container-export-has-mandate',
                ];
        },
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => '50'],
        'bordered' => false,
        'condensed' => false,
        'striped' => true,
        'hover' => true,
        'tableOptions' => ['id' => 'table-export-has-mandate', 'class' => 'table table-form dt-responsive nowrap w-100 dataTable
no-footer dtr-inline'],
        'options' => ['style' => 'table-layout:fixed'],
        'containerOptions' => ['style' => '', 'class' => 'table-container-form'],
        'headerContainer' => ['class' => 'tbl-header', 'style' => ''],
        'footerContainer' => ['class' => '', 'style' => ''],
        'headerRowOptions' => ['class' => '', 'style' => 'font-weight:bold;'],
        'footerRowOptions' => ['class' => '', 'style' => 'font-weight:bold;'],
        'showFooter' => false,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'pjax-container-export-has-mandate']],
        'toolbar' => [$btCreate],
    ]); ?>

</div>