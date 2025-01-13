<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assignments */
/* @var $users app\models\Users[] */

$this->title = 'มอบหมายคำร้อง';
?>
<div class="assignments-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,  // เพิ่มบรรทัดนี้
    ]) ?>
</div>