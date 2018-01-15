<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ifns */

$this->title = 'Иземенить налоговый орган: ' . $model->code_no;
$this->params['breadcrumbs'][] = ['label' => 'Налоговые органы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code_no, 'url' => ['view', 'id' => $model->code_no]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="ifns-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
