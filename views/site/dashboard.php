<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Dashboard';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="dashboard container-fluid">
    <!-- หัวข้อ -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="mb-2 mb-md-0"><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </div>

    <!-- การ์ดสถิติ -->
    <div class="row mb-4">
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">คำร้องทั้งหมด</h6>
                            <h3 class="mb-0"><?= $totalTickets ?></h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-ticket-detailed text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">รอดำเนินการ</h6>
                            <h3 class="mb-0"><?= $pendingTickets ?></h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">กำลังดำเนินการ</h6>
                            <h3 class="mb-0"><?= $inProgressTickets ?></h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-gear-wide-connected text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">เสร็จสิ้น</h6>
                            <h3 class="mb-0"><?= $completedTickets ?></h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ตารางคำร้องล่าสุด -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>คำร้องล่าสุด</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">ลำดับ</th>
                            <th style="width: 80px;" class="text-center">รหัส</th>
                            <th style="min-width: 200px;">ชื่อคำร้อง</th>
                            <th style="min-width: 120px;" class="text-center">หมวดหมู่</th>
                            <th style="min-width: 140px;" class="text-center">สถานะ</th>
                            <th style="min-width: 120px;" class="text-center">วันที่ขอใช้บริการ</th>
                            <th style="min-width: 100px;" class="text-center">วันที่สร้าง</th>
                            <th style="min-width: 150px;" class="text-center">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestTickets as $index => $ticket): ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td class="text-center"><?= $ticket->id ?></td>
                                <td><?= Html::encode($ticket->title) ?></td>
                                <td class="text-center">
                                    <span class="text-dark">
                                        <?= $ticket->getCategoryLabel() ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $ticket->getStatusBadgeClass() ?>">
                                        <?= $ticket->getStatusLabel() ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?= $ticket->service_date ? Yii::$app->formatter->asDate($ticket->service_date, 'php:d/m/Y') : '-' ?>
                                </td>
                                <td class="text-center">
                                    <?= Yii::$app->formatter->asDate($ticket->created_at, 'php:d/m/Y') ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?= Html::a(
                                            '<i class="bi bi-eye"></i>',
                                            ['tickets/view', 'id' => $ticket->id],
                                            [
                                                'class' => 'btn btn-primary btn-action',
                                                'title' => 'ดูรายละเอียด',
                                                'data-bs-toggle' => 'tooltip',
                                            ]
                                        ) ?>
                                        <?php if (Yii::$app->user->identity->role === 'admin'): ?>
                                            <?= Html::a(
                                                '<i class="bi bi-pencil"></i>',
                                                ['tickets/update', 'id' => $ticket->id],
                                                [
                                                    'class' => 'btn btn-warning btn-action',
                                                    'title' => 'แก้ไข',
                                                    'data-bs-toggle' => 'tooltip',
                                                ]
                                            ) ?>
                                            <?= Html::a(
                                                '<i class="bi bi-trash"></i>',
                                                ['tickets/delete', 'id' => $ticket->id],
                                                [
                                                    'class' => 'btn btn-danger btn-action',
                                                    'title' => 'ลบ',
                                                    'data-bs-toggle' => 'tooltip',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?'
                                                ]
                                            ) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
    /* Card Styles */
    .card {
        border: none;
        border-radius: 15px;
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }



    .stat-icon {
        opacity: 0.8;
    }

    /* Table Styles */
    .table th {
        background-color: #f8f9fa;
        vertical-align: middle;
        font-weight: 500;
    }

    .table td {
        vertical-align: middle;
    }

    /* Action Buttons */
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

    /* Button Colors */
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

    /* Hover Effects */
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

    /* Responsive */
    @media (max-width: 768px) {
        .grid-view {
            font-size: 14px;
        }

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

        .badge {
            font-size: 12px;
        }
    }
</style>