<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ifns */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ifns-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code_no')->textInput() ?>

    <?= $form->field($model, 'name_no')->textInput() ?>

    <?= $form->field($model, 'disable_no')->checkbox() ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', 
        	['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
