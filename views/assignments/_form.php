<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Assignments $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $users */
/* @var $model app\models\Assignments */
/* @var $users app\models\Users[] */

?>

<div class="assignments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'assigned_to')->dropDownList(
        ArrayHelper::map($users, 'id', 'name'),
        ['prompt' => 'เลือกผู้ใช้สำหรับมอบหมาย']
    )->label('มอบหมายให้') ?>

    <?= $form->field($model, 'status')->dropDownList([
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed'
    ], ['prompt' => 'เลือกสถานะ'])->label('สถานะ') ?>

    <div class="form-group">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>