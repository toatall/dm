<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Group;
use app\models\Period;
use app\models\Department;
use app\models\Reestr;

/* @var $this yii\web\View */
/* @var $model app\models\Reestr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reestr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_group')->dropDownList(ArrayHelper::map(Group::find()->orderBy('group_number')->all(), 'id', 'allName')) ?>
       
    <?= $form->field($model, 'departments')->checkboxList(ArrayHelper::map(Department::find()->orderBy('AD_groupName')->all(), 'id', 'name')) ?>
    
    <?= $form->field($model, 'type_violation')->dropDownList(Reestr::typeList()) ?>

    <?= $form->field($model, 'number_model')->textInput() ?>

    <?= $form->field($model, 'number_typical')->textInput() ?>

    <?= $form->field($model, 'name_violation')->textInput() ?>
    
    <?= $form->field($model, 'regulations')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'period')->dropDownList(Period::periods()) ?>    

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
