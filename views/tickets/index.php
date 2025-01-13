<?php

use app\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TicketsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'คำร้องทั้งหมด';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex justify-content-end mb-3">
        <?= Html::a('+ เพิ่มคำร้อง', ['create'], [
            'class' => 'btn btn-success',
            'style' => 'font-size: 16px; padding: 10px 20px; font-weight: bold;'
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'ลำดับ'],

            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'title',
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'category',
                'label' => 'หมวดหมู่',
                'value' => function ($model) {
                    return $model->getCategoryLabel(); // แปลงค่าภาษาอังกฤษเป็นภาษาไทย
                },
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'status',

                'content' => function ($model) {
                    return Html::tag('span', Html::encode($model->status), [
                        'class' => $model->status === 'approved' ? 'badge bg-success' : 'badge bg-secondary',
                    ]);
                },
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d/m/Y'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Yii::$app->user->identity->role === 'admin' ? '{view} {update} {delete}' : '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="bi bi-eye"></i> ดูรายละเอียด', $url, ['class' => 'btn btn-primary btn-sm']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="bi bi-pencil"></i> แก้ไข', $url, ['class' => 'btn btn-warning btn-sm']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="bi bi-trash"></i> ลบ', $url, [
                            'class' => 'btn btn-danger btn-sm',
                            'data-method' => 'post',
                            'data-confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?',
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'text-align: center; white-space: nowrap;'],
            ],
        ],
    ]); ?>
</div>