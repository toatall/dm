<?php

use app\models\Period;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="well">
	<p>№ пункта (типовой программы): <strong><?= $model->number_model ?></strong></p>
	<p>№ пункта (типичного нарушения): <strong><?= $model->number_typical ?></strong></p>
	<p>Вопрос: <strong><?= $model->name_violation ?></strong></p>

	<h3>Выбор периода</h3>
	<div class="form-inline">
		<div class="form-group">
			<?= Html::dropDownList('period_type', $model->period, Period::periodsByType($model->period), ['id'=>'period_type', 'class'=>'form-control']) ?>
			<?= Html::dropDownList('period_year', date('Y'), Period::periodYears(), ['id'=>'period_year', 'class'=>'form-control']) ?>
			<?= Html::button('Обновить', ['class'=>'btn btn-primary', 'id'=>'btn-refresh']) ?>
		</div>
	</div>		
</div>

<div id="container-body"></div>

<script type="text/javascript">


	
	function sendFormData(url, data)
	{
		$('#container-body').html('<img src="/css/hourglass.gif" />');
		$.post(url, data)
			.done(function(data) {
				$('#container-body').html(data);
			})
			.error(function(xhr, textStatus) {
				$('#container-body').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
			});;
	}

	function loadForm()
	{
		$(document).ready(function() {
			
			var url = '<?= Url::to(['reestr/violation', 'id'=>$model->id, 'type'=>'_periodType_', 'year'=>'_periodYear_']) ?>';
			url = url.replace('_periodType_', $('#period_type').val());
			url = url.replace('_periodYear_', $('#period_year').val());
			
			$('#container-body').html('<img src="/css/hourglass.gif" />');
				$.get(url)
				.done(function(data) {
					$('#container-body').html(data);
				})
				.error(function(xhr, textStatus) {
					$('#container-body').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
				});
		});
	}

	$('#period_type').on('change', function() {
		loadForm();
	});

	$('#period_year').on('change', function() {
		loadForm();
	});

	$('#btn-refresh').on('click', function() {
		loadForm();
	});

	loadForm();
	
</script>