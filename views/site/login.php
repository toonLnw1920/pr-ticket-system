<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Log In';
$this->registerCssFile('@web/css/login.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>
<div class="site-login">
    <div class="login-container">
        <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}", // ไม่แสดงไอคอน
            ],
        ]); ?>

        <?= $form->field($model, 'email', [
            'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter your email'],
        ]) ?>

        <?= $form->field($model, 'password', [
            'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter your password'],
        ])->passwordInput() ?>

        <div class="form-group mt-4 text-center">
            <?= Html::submitButton('Login', ['class' => 'btn btn-custom btn-lg w-100 text-white', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>