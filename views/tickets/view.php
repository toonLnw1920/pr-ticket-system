<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var app\models\Attachments[] $attachments */

$this->title = 'รายละเอียดคำร้อง #' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'คำร้องทั้งหมด', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'คุณต้องการลบคำร้องนี้หรือไม่?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

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
            'description:ntext',
            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h3>ไฟล์แนบ</h3>
    <ul>
        <?php foreach ($attachments as $attachment): ?>
            <li>
                <?= Html::a(
                    basename($attachment->file_path),
                    ['download', 'path' => $attachment->file_path],
                    ['class' => 'btn btn-link']
                ) ?>
            </li>
        <?php endforeach; ?>
    </ul>

</div>