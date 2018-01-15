<style type="text/css">
table td, table th {
	border: 1px solid #dddddd;
}
</style>
<?php

use app\models\Report;
use yii\bootstrap\Modal;
use yii\bootstrap\Html;

?>
<div class="ifns-index">
  
	
	<?php 
	
		$report = $model->report;			
	?>
	
	<?php if (count($report)): ?>	
	
	<table class="items table">
		<tr style="background-color: #f3f3f3; color: #222;">
	        <th rowspan="3">№ пункта (типовой программы)</th>
	        <th rowspan="3">№ пункта (типичного нарушения)</th>
	        <th rowspan="3">Краткое описание нарушения</th>        
	        <th rowspan="3">Нормативные документы</th>        
	        <th rowspan="3">Наименование НО</th>
<?php
    foreach ($report['periods'] as $period)
    {    	    		    
?>
        	<th colspan="6" style="text-align: center;"><?= $period['year'] . ' ' . $period['periodName'] ?></th>
<?php    	
    }
?>
    </tr>    
    <tr style="background-color: #f3f3f3; color: #222;">
<?php
	foreach ($report['periods'] as $period)	
    {    	
?>    
        <th colspan="3">Проверено документов</th>
        <th rowspan="2">Кол-во нарушений (% от общего количества)</th>
        <th rowspan="2">Сумма нарушений, тыс.руб.</th>
        <th rowspan="2">Динамика</th>
<?php
    }
?>
    </tr>
    <tr style="background-color: #f3f3f3; color: #222;">
<?php
	foreach ($report['periods'] as $period)
    {    	
?>
        <th>Всего</th>
        <th>в т.ч. документов, по которым выявлены нарушения</th>
        <th>количество неустраненных нарушений</th>
<?php
    }
?>
    </tr>
	
	<?php 
		
		// количество инспекций
		$rowSpan = count($model->ifns);
	
		// обход групп (begin)
		foreach ($report['data'] as $rowGroup):
			
			$current=0;
		
	?>
		<tr style="font-weight: bold; background-color: #FDE9D9;">
	        <td style="vertical-align: middle;" rowspan="<?php echo $rowSpan; ?>"><?php echo $rowGroup['group_number']; ?></td>
	        <td style="vertical-align: middle;" colspan="3" rowspan="<?php echo $rowSpan; ?>"><?php echo $rowGroup['group_name']; ?></td>
			<?php 
				
				// обход инспекций (begin)
				foreach ($model->ifns as $ifns):
				
					$tdStyle = ($ifns=='8600' ? ' style="background-color: #FFFFCC;"' : '');
			?>
			<?php  if ($current>0) { ?><tr><?php } ?>
			<td<?= $tdStyle ?>><?= $ifns ?></td>
				
				<?php
					// обход периодов (begin)
					foreach ($report['periods'] as $period):
			?>
			<td<?= $tdStyle ?> title="Проверено документов (всего) (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
				<?= (isset($rowGroup ['ifns']  [$ifns] [$period['year']] [$period['period']] ['doc_all']) 
						? $rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_all'] : '') ?>
			</td>
			
			<td<?= $tdStyle ?> title="Проверено документов (в т.ч. документов, по которым выявлены нарушения) (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
				<?= (isset($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation']) 
						? $rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation'] : '') ?>
			</td>
			
			<td<?= $tdStyle ?> title="Проверено документов (количество неустраненных нарушений (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
				<?= (isset($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation_irr']) 
						? $rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation_irr'] : '') ?>
			</td>
			
			<td<?= $tdStyle ?> title="Кол-во нарушений (% от общего количества) (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
				<?= (isset($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent'])						
						? round($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent'], 2) . ' %' : '0') ?>
			</td>
			
			<td<?= $tdStyle ?> title="Сумма нарушений, тыс.руб. (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
				<?= (isset($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['summ_violation']) 
						? round($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['summ_violation'],2) : '') ?>
			</td>
			
			<td<?= $tdStyle ?> title="Динамика (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
				<?php 					
					if (isset($period['previos']) && $period['previos'] !== null && isset($report['periods'][$period['previos']]))
					{
						$previos = $report['periods'][$period['previos']];
						
						if (isset($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent']) && 
							isset($rowGroup ['ifns'] [$ifns] [$previos['year']] [$previos['period']] ['persent']))
						{
							echo round($rowGroup ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent']
								- $rowGroup ['ifns'] [$ifns] [$previos['year']] [$previos['period']] ['persent'], 2) . ' %';
						}
				?>
					
				<?php 
					}					
				?>
			</td>
			
			<?php 	
					// обход периодов (end)
					endforeach;
				?>
			<?php  if ($current<(count($model->ifns)-1)) { ?></tr><?php } ?>
			
			<?php
				
			$current++;
				
				// обход инспекций (end)
				endforeach;
			?>		
		</tr>
		
		
		
	<?php
	
			// ВОПРОСЫ
			
			$flagTypeViolation=0;
			
			// обход вопросов (begin)
			foreach ($rowGroup as $rowViolation):
			
				if (!isset($rowViolation['number_model']))
					continue;
					
				if ($flagTypeViolation != $rowViolation['type_violation'])
				{
					if ($rowViolation['type_violation'] == 1)
					{
						$bkColor = Report::COLOR_TYPICAL_VIOLATION;
						$trText = 'Перечень вопросов по типичным (системным) нарушениям';
					}
					else
					{
						$bkColor = Report::COLOR_NOT_TYPICAL_VIOLATION;
						$trText = 'Перечень вопросов, не отнесенных к перечню типичных (системных) нарушений';
					}
					?>
				    <tr style="background-color: <?php echo $bkColor; ?>; text-align: left;">
				        <td colspan="<?php echo (count($report['periods'])*6 + 6) ?>"><?php echo $trText; ?></td>
				    </tr>                
				                <?php
					$flagTypeViolation = $rowViolation['type_violation'];
	            }
				?>
				
	            <tr>
	            	<td style="vertical-align: middle;" rowspan="<?php echo $rowSpan; ?>">
	            		<?php echo $rowViolation['number_model']; ?>
	            	</td>
	            	<td style="vertical-align: middle;" rowspan="<?php echo $rowSpan; ?>">
	            		<?php echo $rowViolation['number_typical']; ?>
	            	</td>
	            	<td style="vertical-align: middle;" rowspan="<?php echo $rowSpan; ?>">
	            		<?php echo $rowViolation['name_violation']; ?>
	            	</td>
	            	<td style="vertical-align: middle;" rowspan="<?php echo $rowSpan; ?>">
	            		<?php echo $rowViolation['regulations']; ?>
	            	</td>
	            	
	            	<?php 
	            	
	            	
	            	$currentViolation=0;
	            	
	            	// обход инспекций (begin)
	            	foreach ($model->ifns as $ifns):
	            		
	            		$tdStyle = ($ifns=='8600' ? ' style="background-color: #FFFFCC;"' : '');
	            	?>	            	
	            	<?php  if ($currentViolation>0) { ?><tr><?php } ?>
	            	<td<?= $tdStyle ?>><?= $ifns ?></td>
	            	
	            	<?php
	            		// обход периодов (begin)
	            		foreach ($report['periods'] as $period):
	            	?>
	            	<td<?= $tdStyle ?> title="Проверено документов (всего) (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
	            		<?= (isset($rowViolation ['ifns']  [$ifns] [$period['year']] [$period['period']] ['doc_all']) 
	            			? $rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_all'] : '') ?>
	            	</td>
	            			
            		<td<?= $tdStyle ?> title="Проверено документов (в т.ч. документов, по которым выявлены нарушения) (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
            			<?php 
            				if (isset($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation']))
            				{
	            				if ((intval($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation']) > 0) && !$excel)
	            				{
	            					echo Html::a($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation'], 
	            						['report/violation', 'id_reestr'=>$rowViolation['id_reestr'], 'period'=> $period['period'], 
	            								'periodYear'=> $period['year'], 'ifns'=>$ifns], 
	            						[
            								'data'=>['toggle'=>'modal', 'target'=>'#files'],
            								'class'=>'btn btn-danger',
            								'onclick'=>'loadAjaxViolation($(this).attr(\'href\'));',
	            						]);
	            				}
	            				else
	            				{
	            					echo $rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation'];
	            				}
            				}
            			?>            		
            		</td>
	            			
            		<td<?= $tdStyle ?> title="Проверено документов (количество неустраненных нарушений (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
            			<?= (isset($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation_irr']) 
            				? $rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['doc_violation_irr'] : '') ?>
            		</td>
	            			
	            	<td<?= $tdStyle ?> title="Кол-во нарушений (% от общего количества) (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
	            		<?= (isset($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent'])						
	            			? round($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent'], 2) . ' %' : '0') ?>
	            	</td>
	            			
	            	<td<?= $tdStyle ?> title="Сумма нарушений, тыс.руб. (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
	            		<?= (isset($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['summ_violation']) 
	            			? round($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['summ_violation'],2) : '') ?>
	            	</td>
	            			
	            	<td<?= $tdStyle ?> title="Динамика (<?= $period['periodName'] . ' ' . $period['year'] ?>)">
	            		<?php 					
	            			if (isset($period['previos']) && $period['previos'] !== null && isset($report['periods'][$period['previos']]))
	            			{
	            				$previos = $report['periods'][$period['previos']];
	            				
	            				if (isset($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent']) && 
	            					isset($rowViolation ['ifns'] [$ifns] [$previos['year']] [$previos['period']] ['persent']))
	            				{
	            					echo round($rowViolation ['ifns'] [$ifns] [$period['year']] [$period['period']] ['persent']
	            						- $rowViolation ['ifns'] [$ifns] [$previos['year']] [$previos['period']] ['persent'], 2) . ' %';
	            				}
	            			?>
	            					
	            			<?php 
	            			}					
	            			?>
	            			</td>
	            			
	            			<?php 	
	            					// обход периодов (end)
	            					endforeach;
	            				?>
	            			<?php  if ($currentViolation<(count($model->ifns)-1)) { ?></tr><?php } ?>
	            			
	            			<?php
	            				
	            			$currentViolation++;
	            				            	      
	            	// обход инспекций (end)
	            	endforeach;
	            ?>		
			
			</tr>
			
			<?php 
			
			// обход вопросов (end)
			endforeach;
		
		// обход групп (end)
		endforeach;	
	?>
	
	</table>

	<?php else: ?>
	<h3>
	Нет данных
	</h3>
	<?php endif; ?>
    
</div>

<?php if (!$excel): ?>

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
		'header'=>'Нарушения', 
		'id'=>'files',		
		//'size'=>'modal-fullscreen',
	]);
	?>
		
		<div id="modalBody"></div>
	<?php 
	Modal::end();
	
?>
<?php endif; ?>