<?php


use app\models\Reestr;
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\models\Period;
use yii\helpers\ArrayHelper;
use app\models\Group;
use app\models\Department;

/* @var $this yii\web\View */

$this->title = 'Дистранционный мониторинг';
?>


<div class="site-index">
	
	<div class="well form-inline1">					
		<div class="form-horizontal">
		<?= Html::beginForm(['site/index'], 'GET') ?>
		
			<div class="form-group">
				<label class="col-sm-2 control-label">Вид вопроса</label>
				<div class="col-sm-10">
				<?= Html::dropDownList('type', $type, [
					0 => 'Все',
					Reestr::TYPE_VIOLATION_MODEL => Reestr::TYPE_VIOLATION_DESCRIPTION_MODEL,
					Reestr::TYPE_VIOLATION_TYPICAL => Reestr::TYPE_VIOLATION_DESCRIPTION_TYPICAL,
				], ['class'=>'form-control']) ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Раздел</label>	
				<div class="col-sm-10">	 
				<?= Html::dropDownList('group', $group, 
					[0=>'Все'] + ArrayHelper::map(Group::findByAccess()->all(), 'id', 'allName')
				, ['class'=>'form-control']) ?>
				</div>
			</div>			
			<div class="form-group">
				<label class="col-sm-2 control-label">Отдел</label>	
				<div class="col-sm-10">	 				
				<?= Html::dropDownList('department', $department, 
					[0=>'Все'] + ArrayHelper::map(Department::findAccess()->orderBy('AD_groupName')->all(), 'id', 'name')
				, ['class'=>'form-control']) ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-2 control-label">
					<input type="submit" value="Изменить" class="btn btn-primary" />
				</div>
						
			</div>
		<?= Html::endForm() ?>
		</div>
	</div>
	
	<table class="items table">
   		<tr class="pub-table-th">
   			<th>№ пункта (типовой программы)</th>
   			<th>№ пункта (типичного нарушения)</th>
   			<th>Краткое описание нарушения</th>
   			<th>Нормативные документы</th>   			
   			<th>Отделы</th>
   			<th>Функции</th>
   		</tr>
<?php 
	$groupNum = null;
	$typeViolation = null;
	$typeViolationStyle = null;
	
	foreach ($model as $m):
		
	
		// наименование группы
		if ($groupNum != $m['group_number'])
		{
		?>		
		<tr class="pub-table-group">
			<td colspan="6"><?= $m['group_number'] . '. ' . $m['group_name'] ?></td>
		</tr>		
		<?php 		
			$groupNum = $m['group_number'];
			$flagTypeViolation=null;
		}
		
		// типовое или типичное нарушение
		if ($flagTypeViolation != $m['type_violation'])
		{
			$typeViolationText = ($m['type_violation'] == Reestr::TYPE_VIOLATION_TYPICAL ? Reestr::TYPE_VIOLATION_DESCRIPTION_TYPICAL : Reestr::TYPE_VIOLATION_DESCRIPTION_MODEL);
			$typeViolationStyle = ($m['type_violation'] == Reestr::TYPE_VIOLATION_TYPICAL ? 'pub-table-typical' : 'pub-table-model');
			
		?>			
		<tr>
			<td colspan="6" class="<?= $typeViolationStyle ?>"><?= $typeViolationText ?></td>
		</tr>
		<?php 
			$flagTypeViolation = $m['type_violation'];
		}
		
		
		// реестр нарушений
?>
		<tr>
			<td><?= $m['number_model'] ?></td>
			<td><?= $m['number_typical'] ?></td>
			<td><?= $m['name_violation'] ?></td>
			<td><?= $m['regulations'] ?></td>
			<td><?= str_replace('/', '<br />', $m['departments']) ?></td>
			<td><?= Html::a('Показатели', ['reestr/period', 'id'=>$m['id']],
				[
					'data'=>['toggle'=>'modal', 'target'=>'#modalViolation'], 
					'class'=>'btn btn-primary', 
					'onclick'=>'loadAjaxViolation($(this).attr(\'href\'));',						
			]) ?>
		</tr>
<?php 

	endforeach;
?>   		
   	</table>
      
</div>

<script type="text/javascript">
<!--
	function loadAjaxViolation(url)
	{
		$('#modalBody').html('<img src="/css/hourglass.gif" />');
		$.get(url)
		.done(function(data) {
			$('#modalBody').html(data);
		})
		.error(function(xhr, textStatus) {
			$('#modalBody').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
		});
	}

//-->
</script>

<?php 

	Modal::begin([
		'header'=>'Показатели', 
		'id'=>'modalViolation',		
		'size'=>'modal-fullscreen',
	]);
	?>
		
		
		<div id="modalBody"></div>
	<?php 
	Modal::end();
	
?>
	
