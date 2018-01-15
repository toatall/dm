<?php

use yii\grid\GridView;

?>

<?= GridView::widget([
		'dataProvider' => $model['description'],		
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			//'id',
			'code_no',
			'node',
		],
]); ?>