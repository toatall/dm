<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ReestrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Реестр нарушений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reestr-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {
            if ($model->date_delete != null)
                return ['class'=>'danger'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_group',
            'number_model',
            'number_typical',
            'name_violation',            
            // 'regulations:ntext',
            'periodTypeName',
            // 'period_dop',
            // 'description:ntext',
            'date_create',
            //'date_edit',
            //'date_delete',
            // 'log_change:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
