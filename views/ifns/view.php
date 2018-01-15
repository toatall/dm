<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ifns */

$this->title = $model->code_no;
$this->params['breadcrumbs'][] = ['label' => 'Налоговые органы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ifns-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->code_no], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->code_no], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code_no',
            'name_no',
            'disable_no',
            'date_create',
            'date_edit',
            'log_change:ntext',
        ],
    ]) ?>

</div>
