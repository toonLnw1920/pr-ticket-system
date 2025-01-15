<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCssFile('@web/css/_form.css', ['depends' => [yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="tickets-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <!-- หัวข้อคำร้อง -->
    <?= $form->field($model, 'title')->textInput([
        'placeholder' => 'ใส่หัวข้อ',
    ])->label('หัวข้อคำร้อง') ?>

    <!-- คำอธิบาย -->
    <?= $form->field($model, 'description')->textarea([
        'rows' => 4,
        'placeholder' => 'อธิบายคำร้อง',
    ])->label('คำอธิบาย') ?>

    <!-- ประเภท -->
    <?= $form->field($model, 'category')->dropDownList([
        'news' => 'ข่าว',
        'design' => 'ออกแบบ',
        'photo' => 'ถ่ายภาพ',
        'media' => 'ทำสื่อ',
    ], ['prompt' => 'เลือกประเภทคำร้อง'])->label('ประเภท') ?>

    <!-- วันที่ขอใช้บริการ -->
    <?= $form->field($model, 'created_at')->widget(DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'DD/MM/YYYY'],
    ])->label('วันที่ขอใช้บริการ') ?>

    <!-- อัปโหลดไฟล์ -->
    <div class="form-group">
        <label>อัปโหลดไฟล์</label>
        <input type="file" id="fileUpload" name="Tickets[uploadedFiles][]" multiple class="form-control">
        <div id="fileList" class="mt-2"></div>
    </div>

    <!-- ปุ่มส่งคำร้อง -->
    <div class="form-actions">
        <?= Html::submitButton('ส่งคำร้อง', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>