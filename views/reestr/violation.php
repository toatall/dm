<?php

use yii\bootstrap\Html;
use yii\helpers\Url;


?>
<div class="reestr-form" id="reestr-form">
	
	
	<?php if (count($errorsValidate)): ?>
		<div class="alert alert-danger">
			<?php 
			foreach ($errorsValidate as $code=>$error)
			{
			?>
			<strong>Ошибки по ИФНС <?= $code ?></strong><br />
			<?= $error ?><br /><br />
			<?php 				
			}			
			?>
		</div>
	
	<?php endif; ?>
	
	
	<?= Html::beginForm('', 'post', ['id'=>'form-data']) ?>
	
	<table class="table table-bordered">
		<tr>
			<th rowspan="2">Инспекция</th>
			<th colspan="3">Проверено документов</th>
			<th rowspan="2">Сумма нарушений, тыс.руб.</th>
			<th rowspan="2">Превышение срока</th>
			<th rowspan="2">Примечание (ссылка на приложения)</th>
			<th rowspan="2">Файлы</th>
		</tr>
		<tr>
			<th>Всего</th>
			<th>в т.ч. документов, по которым выявлены нарушения</th>
			<th>количество неустраненных нарушений </th>
		</tr>
		<?php 
		foreach ($modelData as $code_no=>$m)
		{
			
			?>
		<tr<?= ($m->isPost && $m->id !== null ? ' class="info"' : '') ?>>
			<td><?= $code_no ?></td>
			<td style="width: 150px;">
				<div class="form-group<?= (isset($m->errors['doc_all']) ? ' has-error' : (($m->isPost) ? ' has-success' : '')) ?>">			
					<?= Html::activeTextInput($m, 'doc_all', ['name'=>'Data[' . $code_no . '][doc_all]', 'class'=>'form-control']) ?>
				</div>
			</td>
			<td style="width: 150px;">
				<div class="form-group<?= (isset($m->errors['doc_violation']) ? ' has-error' : (($m->isPost) ? ' has-success' : '')) ?>">
					<?= Html::activeTextInput($m, 'doc_violation', ['name'=>'Data[' . $code_no . '][doc_violation]', 'class'=>'form-control']) ?>
				</div>
			</td>
				
			<td style="width: 150px;">
				<div class="form-group<?= (isset($m->errors['doc_violation_irr']) ? ' has-error' : (($m->isPost) ? ' has-success' : '')) ?>">
					<?= Html::activeTextInput($m, 'doc_violation_irr', ['name'=>'Data[' . $code_no . '][doc_violation_irr]', 'class'=>'form-control']) ?>
				</div>
			</td>
			<td style="width: 150px;">
				<div class="form-group<?= (isset($m->errors['summ_violation']) ? ' has-error' : (($m->isPost) ? ' has-success' : '')) ?>">
					<?= Html::activeTextInput($m, 'summ_violation', ['name'=>'Data[' . $code_no . '][summ_violation]', 'class'=>'form-control']) ?>
				</div>
			</td>
			<td style="width: 150px;">
				<div class="form-group<?= (isset($m->errors['exceeding_duration']) ? ' has-error' : (($m->isPost) ? ' has-success' : '')) ?>">
					<?= Html::activeTextInput($m, 'exceeding_duration', ['name'=>'Data[' . $code_no . '][exceeding_duration]', 'class'=>'form-control']) ?>
				</div>
			</td>
			<td>
				<div class="form-group<?= (isset($m->errors['node']) ? ' has-error' : (($m->isPost) ? ' has-success' : '')) ?>">
					<?= Html::activeTextarea($m, 'node', ['name'=>'Data[' . $code_no . '][node]', 'class'=>'form-control']) ?>
				</div>
			</td>				
			<td>
				<?php if ($m->id !== null): ?>
					<?php 
					$countFiles = count($m->file);
					?>
				
				<button type="button" class="btn btn-primary" onclick="loadFilesPanel(<?= ($m->id!==null) ? $m->id : 'null' ?>);">Файлы<?= ($countFiles ? ' <span class="badge">' . $countFiles . '</span>' : '') ?></button>				
				<?php else: ?>
				Запсись не сохранена!
				<?php endif; ?>
			</td>
		</tr>
			<?php 	
		}
		?>		
	</table>   
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php Html::endForm() ?>

</div>


<div id="reestr-files" style="display: none;">
	<div id="reestr-files-body"></div>
	<hr />
	<input type="button" id="button-to-form-panel" class="btn btn-primary" value="Назад" style="margin-top:20px;" >
</div>


<script type="text/javascript">
	// отправка данных для сохранения
	$('#form-data').submit(function() {
		sendFormData('<?= $url ?>', $('#form-data').serialize());
		return false;
	});

	// загрузка панели с файлами
	function loadFilesPanel(id)
	{
		if (id == null)	
		{			
			return false;
		}

		$('#reestr-files-body').html('<img src="/css/hourglass.gif" />');
		
		$('#reestr-form').hide();
		$('#reestr-files').show();
				
		var url = '<?= Url::to(['file/index', 'id_data'=>'_id_data_']) ?>';
		url = url.replace('_id_data_', id);

		$.get(url)
		.done(function(data) {
			$('#reestr-files-body').html(data);
		})
		.error(function(xhr, textStatus) {
			$('#reestr-files-body').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
		});

				
		return false;
	}

	$('#button-to-form-panel').on('click', function() {
		$('#reestr-form').show();
		$('#reestr-files').hide();
	});
	
</script>
