<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReestrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reestr-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_group') ?>

    <?= $form->field($model, 'number_model') ?>

    <?= $form->field($model, 'number_typical') ?>

    <?= $form->field($model, 'name_violation') ?>

    <?php // echo $form->field($model, 'name_model') ?>

    <?php // echo $form->field($model, 'regulations') ?>

    <?php // echo $form->field($model, 'period') ?>

    <?php // echo $form->field($model, 'period_dop') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_edit') ?>

    <?php // echo $form->field($model, 'date_delete') ?>

    <?php // echo $form->field($model, 'log_change') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
