<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var int $totalTickets */
/** @var int $pendingTickets */
/** @var int $completedTickets */
/** @var int $inProgressTickets */
/** @var app\models\Tickets[] $latestTickets */

$this->title = 'Dashboard';
?>

<div class="container">

    <h1 class="mb-4 text-left"><?= Html::encode($this->title) ?></h1>

    <!-- แสดงสถิติ -->
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card shadow text-white bg-primary mb-4">
                <div class="card-header">คำร้องทั้งหมด</div>
                <div class="card-body">
                    <h4 class="card-title"><?= $totalTickets ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-white bg-warning mb-4">
                <div class="card-header">รอดำเนินการ</div>
                <div class="card-body">
                    <h4 class="card-title"><?= $pendingTickets ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-white bg-info mb-4">
                <div class="card-header">ระหว่างดำเนินการ</div>
                <div class="card-body">
                    <h4 class="card-title"><?= $inProgressTickets ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-white bg-success mb-4">
                <div class="card-header">เสร็จสิ้น</div>
                <div class="card-body">
                    <h4 class="card-title"><?= $completedTickets ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- คำร้องล่าสุด -->
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary text-white text-center">คำร้องล่าสุด</div>
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รหัสคำร้อง</th>
                        <th>ชื่อคำร้อง</th>
                        <th>หมวดหมู่</th>
                        <th>สถานะ</th>
                        <th>วันที่สร้าง</th>
                        <th>ตัวเลือก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latestTickets as $ticket): ?>
                        <tr>
                            <td><?= Html::encode($ticket->id) ?></td>
                            <td><?= Html::encode($ticket->title) ?></td>
                            <td><?= Html::encode($ticket->getCategoryLabel()) ?></td>
                            <td>
                                <span class="badge bg-<?= $ticket->status === 'completed' ? 'success' : ($ticket->status === 'pending' ? 'warning' : 'info') ?>">
                                    <?= Html::encode($ticket->status) ?>
                                </span>
                            </td>
                            <td><?= Yii::$app->formatter->asDate($ticket->created_at, 'php:d/m/Y') ?></td>
                            <td class="text-center">
                                <?= Html::a('<i class="bi bi-eye"></i> ดูรายละเอียด', ['tickets/view', 'id' => $ticket->id], ['class' => 'btn btn-primary btn-sm', 'title' => 'ดู']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>