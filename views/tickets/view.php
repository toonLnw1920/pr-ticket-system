<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var app\models\Attachments[] $attachments */

$this->title = 'รายละเอียดคำร้อง #' . $model->id;
$this->registerCssFile('@web/css/view.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<body>
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
            <!-- ส่วนรายละเอียดคำร้อง -->
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
                                    'contentOptions' => ['style' => 'text-align: left; white-space: pre-wrap;'],
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
                                            ['class' => 'text-decoration-none', 'title' => 'ดาวน์โหลดไฟล์นี้']
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

            <!-- 🆕 ส่วนการมอบหมายคำร้อง -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">เจ้าหน้าที่ผู้รับคำร้อง</div>
                <div class="card-body">
                    <?php if ($model->status === 'pending' && Yii::$app->user->can('assignTicket')): ?>
                        <?= Html::a('มอบหมายคำร้อง', ['assignments/create', 'ticket_id' => $model->id], ['class' => 'btn btn-primary mb-3']) ?>
                    <?php endif; ?>

                    <h5>ประวัติการมอบหมาย</h5>
                    <ul class="list-group">
                        <?php foreach ($model->assignments as $assignment): ?>
                            <li class="list-group-item">
                                <strong>มอบหมายให้:</strong> <?= Html::encode($assignment->assignedTo->name) ?> |
                                <strong>โดย:</strong> <?= Html::encode($assignment->assignedBy->name) ?> |
                                <strong>สถานะ:</strong> <?= Html::encode($assignment->status) ?> |
                                <strong>วันที่มอบหมาย:</strong> <?= Html::encode($assignment->assigned_at) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileUpload = document.getElementById('fileUpload');
            const fileList = document.getElementById('fileList');

            fileUpload.addEventListener('change', function() {
                fileList.innerHTML = ''; // ล้างรายการไฟล์ก่อน

                if (fileUpload.files.length > 0) {
                    const ul = document.createElement('ul');
                    ul.classList.add('list-group');

                    Array.from(fileUpload.files).forEach(file => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                        li.innerHTML = `
                    <span><i class="fas fa-file-alt"></i> ${file.name}</span>
                    <span class="badge bg-success">${(file.size / 1024).toFixed(2)} KB</span>
                `;
                        ul.appendChild(li);
                    });

                    fileList.appendChild(ul);
                } else {
                    fileList.innerHTML = `<p class="text-muted">ยังไม่มีไฟล์ที่เลือก</p>`;
                }
            });
        });
    </script>
</body>