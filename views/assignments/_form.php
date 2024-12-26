<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Assignments $model */
/** @var app\models\Users[] $users */

$this->title = 'มอบหมายคำร้อง';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'assigned_to')->dropDownList(
    \yii\helpers\ArrayHelper::map($users, 'id', 'name'),
    ['prompt' => 'เลือกเจ้าหน้าที่']
) ?>

<?= $form->field($model, 'remarks')->textarea(['rows' => 4]) ?>

<?= $form->field($model, 'status')->dropDownList([
    'pending' => 'รอดำเนินการ',
    'in_progress' => 'กำลังดำเนินการ',
    'completed' => 'เสร็จสิ้น',
]) ?>

<div class="form-group">
    <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>