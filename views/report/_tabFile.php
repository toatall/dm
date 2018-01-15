<?php

use yii\grid\GridView;
use yii\bootstrap\Html;

?>

<?= GridView::widget([
		'dataProvider' => $model['file'],		
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			//'id',
			'data.code_no',
			[
				'attribute' => 'filename_original',
				'value' => function($data) {
					return Html::a($data->filename_original, $data->filename_generate);
				},
				'format' => 'raw',
			],
			'author_name',
		],
]); ?>