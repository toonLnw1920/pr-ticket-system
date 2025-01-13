<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */

$this->title = 'แบบฟอร์มคำร้อง';
//$this->params['breadcrumbs'][] = ['label' => 'แบบคำร้องทั้งหมด', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>