<?php

use app\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;



$this->title = 'คำร้องทั้งหมด';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


<div class="tickets-index container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="mb-2 mb-md-0"><?= Html::encode($this->title) ?></h1>
                <?= Html::a('+ เพิ่มคำร้อง', ['create'], [
                    'class' => 'btn btn-success',
                    'style' => 'font-size: 16px; padding: 10px 20px; font-weight: bold;'
                ]) ?>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['class' => 'grid-view table-responsive'],
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => 'ลำดับ',
                            'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
                            'headerOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'width: 80px; text-align: center;'],
                            'headerOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'title',
                            'contentOptions' => ['style' => 'min-width: 200px; text-align: left;'],
                            'headerOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'category',
                            'label' => 'หมวดหมู่',
                            'value' => function ($model) {
                                return $model->getCategoryLabel();
                            },
                            'contentOptions' => ['style' => 'min-width: 120px; text-align: center;'],
                            'headerOptions' => ['class' => 'text-center'],
                            'filter' => [
                                'news' => 'ข่าว',
                                'design' => 'ออกแบบ',
                                'photo' => 'ถ่ายภาพ',
                                'media' => 'ทำสื่อ',
                            ],
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                return $model->getStatusLabel();
                            },
                            'content' => function ($model) {
                                return Html::tag('span', Html::encode($model->getStatusLabel()), [
                                    'class' => "badge {$model->getStatusBadgeClass()}",
                                ]);
                            },
                            'filter' => [
                                'pending' => 'รอการอนุมัติ',
                                'approved' => 'อนุมัติแล้ว',
                                'rejected' => 'ไม่อนุมัติ',
                                'in_progress' => 'กำลังดำเนินการ',
                                'completed' => 'เสร็จสิ้น'
                            ],
                            'contentOptions' => ['style' => 'min-width: 140px; text-align: center;'],
                            'headerOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'service_date',
                            'format' => ['date', 'php:d/m/Y'],
                            'contentOptions' => ['style' => 'min-width: 100px; text-align: center;'],
                            'headerOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:d/m/Y'],
                            'contentOptions' => ['style' => 'min-width: 100px; text-align: center;'],
                            'headerOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => [
                                'style' => 'min-width: 150px; text-align: center;',
                                'class' => 'action-column'
                            ],
                            'headerOptions' => ['class' => 'text-center'],
                            'template' => '<div class="action-buttons">{view} {update} {delete}</div>',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a(
                                        '<i class="bi bi-eye"></i>',
                                        ['view', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-primary btn-action',
                                            'title' => 'ดูรายละเอียด',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    );
                                },
                                'update' => function ($url, $model) {
                                    if (Yii::$app->user->identity->role === 'admin') {
                                        return Html::a(
                                            '<i class="bi bi-pencil"></i>',
                                            ['update', 'id' => $model->id],
                                            [
                                                'class' => 'btn btn-warning btn-action',
                                                'title' => 'แก้ไข',
                                                'data-bs-toggle' => 'tooltip'
                                            ]
                                        );
                                    }
                                    return '';
                                },
                                'delete' => function ($url, $model) {
                                    if (Yii::$app->user->identity->role === 'admin') {
                                        return Html::a(
                                            '<i class="bi bi-trash"></i>',
                                            ['delete', 'id' => $model->id],
                                            [
                                                'class' => 'btn btn-danger btn-action',
                                                'title' => 'ลบ',
                                                'data-bs-toggle' => 'tooltip',
                                                'data-method' => 'post',
                                                'data-confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?'
                                            ]
                                        );
                                    }
                                    return '';
                                },
                            ],
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// เปิดใช้งาน tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
JS;
$this->registerJs($js);
?>

<style>
    .grid-view {
        overflow-x: auto;
        margin: 0 -15px;
        padding: 0 15px;
    }

    @media (max-width: 768px) {
        .grid-view {
            font-size: 14px;
        }

        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 12px;
        }

        .badge {
            font-size: 12px;
        }
    }

    .table th {
        background-color: #f8f9fa;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

    .action-column {
        padding: 8px !important;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .btn-action i {
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .btn-action {
            width: 28px;
            height: 28px;
        }

        .btn-action i {
            font-size: 12px;
        }

        .action-buttons {
            gap: 4px;
        }
    }

    /* ปรับสีปุ่มให้สวยงามขึ้น */
    .btn-primary.btn-action {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-warning.btn-action {
        background-color: #f1c40f;
        border-color: #f1c40f;
        color: #fff;
    }

    .btn-danger.btn-action {
        background-color: #e74c3c;
        border-color: #e74c3c;
    }

    /* เพิ่ม hover effect */
    .btn-primary.btn-action:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-warning.btn-action:hover {
        background-color: #f39c12;
        border-color: #f39c12;
        color: #fff;
    }

    .btn-danger.btn-action:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }
</style>