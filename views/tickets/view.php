<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'รายละเอียดคำร้อง #' . $model->id;
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid tickets-view">
    <!-- Title Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="mb-2 mb-md-0"><?= Html::encode($this->title) ?></h1>
                <?php if (Yii::$app->user->can('manageTickets') || Yii::$app->user->id === $model->user_id): ?>
                    <div class="action-buttons">
                        <?= Html::a('<i class="bi bi-pencil"></i> แก้ไข', ['update', 'id' => $model->id], [
                            'class' => 'btn btn-warning me-2',
                            'style' => 'font-size: 16px; padding: 10px 20px;'
                        ]) ?>
                        <?= Html::a('<i class="bi bi-trash"></i> ลบ', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'style' => 'font-size: 16px; padding: 10px 20px;',
                            'data' => [
                                'confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mt-3 text-center">
                <span class="badge <?= $model->getStatusBadgeClass() ?> p-2" style="font-size: 1rem;">
                    <i class="bi <?= $model->status === 'approved' ? 'bi-check-circle' : ($model->status === 'rejected' ? 'bi-x-circle' : ($model->status === 'in_progress' ? 'bi-gear' : ($model->status === 'completed' ? 'bi-check-square' : 'bi-clock'))) ?>"></i>
                    <?= Html::encode($model->getStatusLabel()) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Status Management Card -->
        <?php if (Yii::$app->user->can('manageTickets')): ?>
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-gear-fill me-2"></i>จัดการสถานะ</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <?php
                            $statuses = [
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
                                        'style' => 'font-size: 14px; padding: 8px 16px;',
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
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-file-text-fill me-2"></i>รายละเอียดคำร้อง</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered'],
                        'attributes' => [
                            'id',
                            'title',
                            [
                                'attribute' => 'category',
                                'value' => $model->getCategoryLabel(),
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'attribute' => 'description',
                                'format' => 'ntext',
                                'contentOptions' => ['style' => 'white-space: pre-wrap;'],
                            ],
                            [
                                'attribute' => 'service_date',
                                'format' => ['datetime', 'php:d/m/Y'],
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['datetime', 'php:d/m/Y'],
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => ['datetime', 'php:d/m/Y'],
                            ],
                            [
                                'label' => 'ผู้สร้างคำร้อง',
                                'value' => $model->user->name,
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-md-4">

            <!-- Attachments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-paperclip me-2"></i>ไฟล์แนบ</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($attachments)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($attachments as $attachment): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <div class="d-flex align-items-center">
                                        <?php
                                        // กำหนดไอคอนตามประเภทไฟล์
                                        $fileIcon = 'bi-file-earmark';
                                        $fileType = strtolower($attachment->file_type);

                                        if (str_contains($fileType, 'pdf')) {
                                            $fileIcon = 'bi-file-earmark-pdf';
                                        } elseif (str_contains($fileType, 'word') || $fileType === 'docx' || $fileType === 'doc') {
                                            $fileIcon = 'bi-file-earmark-word';
                                        } elseif (str_contains($fileType, 'excel') || $fileType === 'xlsx' || $fileType === 'xls') {
                                            $fileIcon = 'bi-file-earmark-excel';
                                        } elseif (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $fileIcon = 'bi-file-earmark-image';
                                        }
                                        ?>
                                        <i class="bi <?= $fileIcon ?> me-2 text-primary fs-5"></i>
                                        <div class="text-break">
                                            <?= Html::encode(basename($attachment->file_path)) ?>
                                        </div>
                                    </div>
                                    <?= Html::a(
                                        '<i class="bi bi-download"></i>',
                                        ['download', 'path' => $attachment->file_path],
                                        [
                                            'class' => 'btn btn-outline-primary btn-sm ms-3',
                                            'title' => 'ดาวน์โหลด',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    ) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-file-earmark-x mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">ไม่มีไฟล์แนบ</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Assignments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>การมอบหมายงาน</h5>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->user->can('assignTicket') && in_array($model->status, ['approved', 'pending'])): ?>
                        <?= Html::a(
                            '<i class="bi bi-plus-circle"></i> มอบหมายคำร้อง',
                            ['/assignments/create', 'ticket_id' => $model->id],
                            ['class' => 'btn btn-primary mb-3 w-100']
                        ) ?>
                    <?php endif; ?>

                    <?php if (!empty($model->assignments)): ?>
                        <div class="assignments-list">
                            <?php foreach ($model->assignments as $assignment): ?>
                                <div class="assignment-card">
                                    <div class="assignment-header">
                                        <div class="profile-image">
                                        <img src="<?= Yii::getAlias('@web/uploads/img/Profile.jpg') ?>" alt="Profile" class="rounded-circle">
                                        </div>
                                        <div class="assignment-status">
                                            <span class="badge bg-<?= $assignment->status === 'completed' ? 'success' : ($assignment->status === 'in_progress' ? 'info' : 'warning') ?>">
                                                <?= Html::encode($assignment->status === 'completed' ? 'เสร็จสิ้น' : ($assignment->status === 'in_progress' ? 'กำลังดำเนินการ' : 'รอดำเนินการ')) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="assignment-info">
                                        <h6 class="assigned-name"><?= Html::encode($assignment->assignedTo->name) ?></h6>
                                        <div class="contact-details">
                                            <div class="contact-item">
                                                <i class="bi bi-telephone"></i>
                                                <span>02-123-4567</span>
                                            </div>
                                            <div class="contact-item">
                                                <i class="bi bi-envelope"></i>
                                                <span><?= Html::encode($assignment->assignedTo->email) ?></span>
                                            </div>
                                            <div class="contact-item">
                                                <i class="bi bi-building"></i>
                                                <span>แผนก IT Support</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="assignment-footer">
                                        <div class="assignment-date">
                                            <i class="bi bi-calendar2"></i>
                                            <span>มอบหมายเมื่อ: <?= Yii::$app->formatter->asDate($assignment->assigned_at, 'php:d/m/Y') ?></span>
                                        </div>
                                        <?php if ($assignment->status === 'completed'): ?>
                                            <div class="completion-date">
                                                <i class="bi bi-check-circle"></i>
                                                <span>เสร็จสิ้นเมื่อ: <?= Yii::$app->formatter->asDate($assignment->completed_at, 'php:d/m/Y H:i') ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-person-x fs-1 mb-2"></i>
                            <p class="mb-0">ยังไม่มีการมอบหมายงาน</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card Styles */
    .card {
        border: none;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Table Styles */
    .table th {
        background-color: #f8f9fa;
        font-weight: 500;
        vertical-align: middle;
    }

    .detail-view th {
        width: 200px;
    }

    /* Badge Styles */
    .badge {
        padding: 8px 12px;
        border-radius: 6px;
    }

    /* Button Styles */
    .btn {
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    /* File Attachment Styles */
    .list-group-item {
        transition: background-color 0.2s ease;
        border-left: none;
        border-right: none;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .btn-outline-primary {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .badge {
            font-size: 0.8rem;
            padding: 6px 10px;
        }

        .btn {
            font-size: 0.9rem !important;
            padding: 8px 16px !important;
        }

        .detail-view th {
            width: 150px;
        }

        .btn-outline-primary {
            width: 28px;
            height: 28px;
        }
    }

    /* Assignment Item Styles */
    .assignment-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .assignments-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .assignment-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .assignment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .assignment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .profile-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #e9ecef;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .assignment-status .badge {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }

    .assignment-info {
        margin-top: 1rem;
    }

    .assigned-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .contact-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
    }

    .contact-item i {
        font-size: 1rem;
        width: 20px;
    }

    .assignment-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .assignment-date,
    .completion-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>