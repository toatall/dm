<?php

use yii\helpers\Html;
use app\models\Period;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\models\User;



/* @var $this yii\web\View */
/* @var $searchModel app\models\IfnsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->registerCss('.container { margin-left: 0; }');

$this->title = 'Отчет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ifns-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php $form = ActiveForm::begin(['id'=>'form-report', 'action'=>['report/data']]); ?>
	<h3>Настройки отчета</h3>
	<hr />
	
	<div class="well form-inline" style="background: white;">
			<strong>Период</strong><br />
			
			<div class="row">			
				<div class="col-xs-3 form-group">
					<div class="row">
						<div class="col-xs-6">			
							<?= $form->field($model, 'periodBeginMonth')->dropDownList(Period::months()) ?>
						</div>
						<div class="col-xs-6">
							<?= $form->field($model, 'periodBeginYear')->dropDownList(Period::fixedYears()) ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="col-xs-3 form-group">
				<div class="row">
					<div class="col-xs-6">
						<?= $form->field($model, 'periodEndMonth')->dropDownList(Period::months()) ?>
					</div>
					<div class="col-xs-6">
						<?= $form->field($model, 'periodEndYear')->dropDownList(Period::fixedYears()) ?>
					</div>
				</div>
			</div>
		</div>
		
		<?php if (User::inRole(['admin','moderator'])): ?>
		<div class="well">
			<h4>Отделы</h4>
			<button id="btn-expand-departments" class="btn btn-primary">Раскрыть/свернуть</button>
			<div id="content-departments" style="display: none; margin-top: 10px;">				
				<?= Html::checkbox(null, true, ['label'=>'Выделить все', 'id'=>'check_all_department']) ?><br />
				<?= $form->field($model, 'departments')->checkboxList($model->listDepartment, ['separator'=>'<br />'])->label(false) ?>
			</div>
		</div>
		<div class="well">
			<h4>Разделы</h4>
			<button id="btn-expand-groups" class="btn btn-primary">Раскрыть/свернуть</button>
			<div id="content-groups" style="display: none; margin-top: 10px;">				
				<?= Html::checkbox(null, true, ['label'=>'Выделить все', 'id'=>'check_all_group']) ?><br />
				<?= $form->field($model, 'groups')->checkboxList($model->listGroup, ['separator'=>'<br />'])->label(false) ?>
			</div>
		</div>
		<div class="well">
			<h4>Вид вопроса</h4>							
				<?= $form->field($model, 'type')->dropDownList($model->listType)->label(false) ?>
			</div>
		</div>
		<?php endif; ?>
		
		<br />
		<div class="form-group">
        	<?= Html::button('Показать', ['id'=>'btnSubmit', 'class' => 'btn btn-primary']) ?>
        	<?= Html::button('Экспорт в Excel', ['id'=>'btnExport', 'class' => 'btn btn-success']) ?>
    	</div>
    
	</div>
	<?php ActiveForm::end(); ?>
	
	<div id="container-report-data"></div>
	<?php 
			
		
		$this->registerJs("
				
			function unload(exportExcel)
			{	
				
				var dataForm = $('#form-report').serializeArray();
				
				if (!exportExcel)
				{					
					$('#container-report-data').html('<img src=\"/css/hourglass.gif\" />');
				}
				else
				{
					$('#form-report').submit();
					return;
				}
								
				$.ajax({
					url: '" . Url::to(['report/data']) . "',
					type: 'POST',
					data: dataForm,
				})
				.done(function(data) {				
					$('#container-report-data').html(data);				
				})
				.error(function(xhr, textStatus) {				
					$('#container-report-data').html('<div class=\"alert alert-danger\"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');				
				});
								
			}
			
			$('#btnSubmit').on('click', function() { unload(false); });
			$('#btnExport').on('click', function() { unload(true); });	
				
			
			
			
			// отделы
			
			$('#btn-expand-departments').on('click', function() { $('#content-departments').toggle(); return false; });	
				
			$('#check_all_department').on('click', function(){
            	$('input[name=\'Report[departments][]\'\]').prop(\"checked\", $(this).prop(\"checked\"));
            });
			$('input[name=\'Report[departments][]\'\]').prop(\"checked\", true); // почему-то не проставляется галочка в 12 отделе?? через модель Report
			
			// разделы
			
			$('#btn-expand-groups').on('click', function() { $('#content-groups').toggle(); return false; });	
				
			$('#check_all_group').on('click', function(){
            	$('input[name=\'Report[groups][]\'\]').prop(\"checked\", $(this).prop(\"checked\"));
            });
			$('input[name=\'Report[groups][]\'\]').prop(\"checked\", true); // почему-то не проставляется галочки в некоторых checkbox'ах
				
		", \yii\web\View::POS_END);
	
	?>
	
	
	
    
</div>
