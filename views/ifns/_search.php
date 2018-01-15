<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\IfnsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ifns-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'code_no') ?>

    <?= $form->field($model, 'name_no') ?>

    <?= $form->field($model, 'disable_no') ?>

    <?= $form->field($model, 'date_create') ?>

    <?= $form->field($model, 'date_edit') ?>

    <?php // echo $form->field($model, 'log_change') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
