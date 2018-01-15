<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IfnsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="ifns-index">
	<p>
		<strong>ИФНС: <?= $modelData->code_no ?></strong>
	</p>
    <p>
        <?= Html::buttonInput('Добавить файл(ы)', ['class' => 'btn btn-success', 'id'=>'btn-add-files']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,       	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id:integer:ИД',
            'filename_original:text:Имя файла',
            'date_create:text:Дата создания',
            'author_name:text:Автор',

            [
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{delete}',
            	'buttons' => [
	            	'delete' => function ($url, $model, $key) 
	            		{
	    					return Html::button('Удалить', ['class'=>'btn btn-danger', 'onclick'=>'deleteFile(\''. $url . '\'); $(this).prop(\'disabled\', true);']);	
	    				},
	    		],
    		],
        ],
    ]); ?>
</div>

<script type="text/javascript">


	function deleteFile(url)
	{		
		$.get(url)
		.done(function(data) {
			$('#reestr-files-body').html(data);
		})
		.error(function(xhr, textStatus) {
			$('#reestr-files-body').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
		});
	}
	
	$('#btn-add-files').on('click', function(){
		
		$('#reestr-files-body').html('<img src="/css/hourglass.gif" />');
		
		$.get('<?= Url::to(['file/create', 'id_data'=>$modelData->id]) ?>')
			.done(function(data) {
				$('#reestr-files-body').html(data);
			})
			.error(function(xhr, textStatus) {
				$('#reestr-files-body').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
			});
						
	});	
</script>