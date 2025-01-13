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
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="mb-3">
            <span class="badge <?= $model->getStatusBadgeClass() ?> p-2">
                <i class="bi <?= $model->status === 'approved' ? 'bi-check-circle' : ($model->status === 'rejected' ? 'bi-x-circle' : ($model->status === 'in_progress' ? 'bi-gear' : ($model->status === 'completed' ? 'bi-check-square' : 'bi-clock'))) ?>"></i>
                <?= Html::encode($model->getStatusLabel()) ?>
            </span>
        </div>
        <?php if (Yii::$app->user->can('manageTickets') || Yii::$app->user->id === $model->user_id): ?>
            <div class="action-buttons mb-3">
                <?= Html::a('<i class="bi bi-pencil"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-warning me-2']) ?>
                <?= Html::a('<i class="bi bi-trash"></i> ลบ', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Status Management Card -->
        <?php if (Yii::$app->user->can('manageTickets')): ?>
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-gear-fill me-2"></i>จัดการสถานะ
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <?php
                            $statuses = [
                                'pending' => ['label' => 'รอการอนุมัติ', 'icon' => 'bi-clock', 'class' => 'btn-warning'],
                                'approved' => ['label' => 'อนุมัติ', 'icon' => 'bi-check-circle', 'class' => 'btn-success'],
                                'rejected' => ['label' => 'ไม่อนุมัติ', 'icon' => 'bi-x-circle', 'class' => 'btn-danger'],
                                'in_progress' => ['label' => 'กำลังดำเนินการ', 'icon' => 'bi-gear', 'class' => 'btn-info'],
                                'completed' => ['label' => 'เสร็จสิ้น', 'icon' => 'bi-check-square', 'class' => 'btn-primary']
                            ];

                            foreach ($statuses as $status => $info):
                                if ($status !== $model->status):
                            ?>
                                    <?= Html::beginForm(['change-status', 'id' => $model->id], 'post', ['class' => 'd-inline-block mb-2']) ?>
                                    <?= Html::hiddenInput('status', $status) ?>
                                    <?= Html::submitButton("<i class='bi {$info['icon']}'></i> {$info['label']}", [
                                        'class' => "btn {$info['class']}",
                                        'data' => ['confirm' => "คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะเป็น{$info['label']}?"]
                                    ]) ?>
                                    <?= Html::endForm() ?>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ticket Details -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-file-text-fill me-2"></i>รายละเอียดคำร้อง
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'title',
                            [
                                'attribute' => 'category',
                                'value' => $model->getCategoryLabel(),
                            ],
                            [
                                'attribute' => 'description',
                                'format' => 'ntext',
                                'contentOptions' => ['style' => 'white-space: pre-wrap;'],
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['datetime', 'php:d/m/Y H:i:s'],
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => ['datetime', 'php:d/m/Y H:i:s'],
                            ],
                            [
                                'label' => 'ผู้สร้างคำร้อง',
                                'value' => $model->user->name,
                            ],
                        ],
                        'options' => ['class' => 'table table-striped'],
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-md-4">
            <!-- Attachments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-paperclip me-2"></i>ไฟล์แนบ
                </div>
                <div class="card-body">
                    <?php if (!empty($attachments)): ?>
                        <ul class="list-group">
                            <?php foreach ($attachments as $attachment): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <i class="bi bi-file-earmark me-2"></i>
                                    <?= Html::a(
                                        basename($attachment->file_path),
                                        ['download', 'path' => $attachment->file_path],
                                        ['class' => 'text-decoration-none']
                                    ) ?>
                                    <span class="badge bg-primary rounded-pill">
                                        <?= strtoupper($attachment->file_type) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">
                            <i class="bi bi-info-circle me-2"></i>ไม่มีไฟล์แนบ
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Assignments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-person-check-fill me-2"></i>การมอบหมายงาน
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->user->can('assignTicket') && in_array($model->status, ['approved', 'pending'])): ?>
                        <?= Html::a(
                            '<i class="bi bi-plus-circle"></i> มอบหมายคำร้อง',
                            ['/assignments/create', 'ticket_id' => $model->id],
                            ['class' => 'btn btn-primary mb-3 w-100']
                        )
                        ?>
                    <?php endif; ?>

                    <?php if (!empty($model->assignments)): ?>
                        <div class="assignments-history">
                            <?php foreach ($model->assignments as $assignment): ?>
                                <div class="assignment-item border-bottom pb-2 mb-2">
                                    <div>
                                        <i class="bi bi-person-fill me-1"></i>
                                        <strong>ผู้รับมอบหมาย:</strong>
                                        <?= Html::encode($assignment->assignedTo->name) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar2 me-1"></i>
                                        <?= Yii::$app->formatter->asDatetime($assignment->assigned_at) ?>
                                    </div>
                                    <div>
                                        <span class="badge bg-<?= $assignment->status === 'completed' ? 'success' : ($assignment->status === 'in_progress' ? 'info' : 'warning') ?>">
                                            <?= Html::encode($assignment->status) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">
                            <i class="bi bi-info-circle me-2"></i>ยังไม่มีการมอบหมายงาน
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>