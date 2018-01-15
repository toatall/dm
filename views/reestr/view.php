<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Period;

/* @var $this yii\web\View */
/* @var $model app\models\Reestr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Реестр', 'url' => ['index']];
$this->params['breadcrumbs'][] = '#'.$this->title;
?>
<div class="reestr-view">

    <h1>#<?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'group.allName',
        	'departmentsText:raw',
            'number_model',
            'number_typical',
            'name_violation',
            'regulations:ntext',
        	[
        		'attribute' => 'period',
        		'value' => Period::periodValueByKey($model->period),
    		],           
            'description:ntext',
            'date_create',
            'date_edit',
            'date_delete',
            'log_change:ntext',
        ],
    ]) ?>

</div>

