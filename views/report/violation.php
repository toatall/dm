<?php

use yii\bootstrap\Tabs;
use yii\base\Widget;



echo Tabs::widget([
	'items' => [
		[
			'label' => 'Описание',
			'content' => $this->render('_tabDescription', ['model'=>$model]),
			'active' => true,
		],
		[
			'label' => 'Файлы',
			'content' => $this->render('_tabFile', ['model'=>$model]),
		],
	],
]);