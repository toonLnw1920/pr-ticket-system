<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Assignments $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $users */
?>

<div class="assignments-form">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-person-check-fill me-2"></i> มอบหมายงาน
            </h4>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'p-3'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    'options' => ['class' => 'mb-4'],
                ],
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'assigned_to', [
                        'options' => ['class' => 'mb-4'],
                    ])->dropDownList(
                        ArrayHelper::map($users, 'id', 'name'),
                        [
                            'prompt' => '-- กรุณาเลือกผู้รับมอบหมาย --',
                            'class' => 'form-select',
                            'style' => 'height: 45px;'
                        ]
                    )->label('<i class="bi bi-person-fill me-1"></i> มอบหมายให้') ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'status', [
                        'options' => ['class' => 'mb-4'],
                    ])->dropDownList(
                        [
                            'pending' => 'รอดำเนินการ',
                            'in_progress' => 'กำลังดำเนินการ',
                            'completed' => 'เสร็จสิ้น'
                        ],
                        [
                            'prompt' => '-- กรุณาเลือกสถานะ --',
                            'class' => 'form-select',
                            'style' => 'height: 45px;'
                        ]
                    )->label('<i class="bi bi-flag-fill me-1"></i> สถานะ') ?>
                </div>
            </div>

            <div class="form-group text-center mt-4">
                <?= Html::submitButton('<i class="bi bi-check-circle me-1"></i> บันทึก', [
                    'class' => 'btn btn-success px-4',
                    'style' => 'min-width: 130px; font-size: 16px; padding-top: 10px; padding-bottom: 10px;'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<style>
.assignments-form .card {
    border: none;
    border-radius: 8px;
}

.assignments-form .card-header {
    border-radius: 8px 8px 0 0;
    padding: 15px 20px;
}

.assignments-form .card-body {
    padding: 20px;
}

.assignments-form .form-label {
    font-weight: 500;
    margin-bottom: 10px;
}

.assignments-form .form-select,
.assignments-form .form-control {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease;
}

.assignments-form .form-select:focus,
.assignments-form .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.assignments-form .btn {
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.assignments-form .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

@media (max-width: 768px) {
    .assignments-form .card-body {
        padding: 15px;
    }
    
    .assignments-form .btn {
        width: 100%;
    }
}
</style>