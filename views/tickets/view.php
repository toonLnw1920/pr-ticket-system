<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var app\models\Attachments[] $attachments */

$this->title = 'รายละเอียดคำร้อง #' . $model->id;
$this->registerCssFile('@web/css/view.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="container mt-4 tickets-view">

    <!-- Title Section -->
    <div class="text-center mb-4">
        <h1>รายละเอียดคำร้อง #<?= $model->id ?></h1>
        <p>
            <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-warning me-2']) ?>
            <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>

    <div class="row">
        <!-- Left Section: Detail Information -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">รายละเอียดคำร้อง</div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'title',
                            [
                                'attribute' => 'category',
                                'value' => function ($model) {
                                    $categories = [
                                        'news' => 'ข่าว',
                                        'design' => 'ออกแบบ',
                                        'photo' => 'ถ่ายภาพ',
                                        'media' => 'ทำสื่อ',
                                    ];
                                    return $categories[$model->category] ?? 'ไม่ระบุ';
                                },
                            ],

                            [
                                'attribute' => 'description',
                                'format' => 'ntext',
                                'label' => 'คำอธิบาย',
                                'contentOptions' => ['class' => 'text-left'], // ใช้คลาสสำหรับข้อความชิดซ้าย
                            ],

                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'in_progress' => 'info',
                                    ];
                                    return Html::tag('span', $model->status, [
                                        'class' => 'badge bg-' . ($statusColors[$model->status] ?? 'secondary')
                                    ]);
                                },
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Right Section: Attachments -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">ไฟล์แนบ</div>
                <div class="card-body">
                    <?php if (!empty($attachments)): ?>
                        <ul class="list-group">
                            <?php foreach ($attachments as $attachment): ?>
                                <li class="d-flex justify-content-between align-items-center">
                                    <?= Html::a(
                                        basename($attachment->file_path),
                                        ['download', 'path' => $attachment->file_path],
                                        ['class' => 'btn btn-link']
                                    ) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">ไม่มีไฟล์แนบ</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>