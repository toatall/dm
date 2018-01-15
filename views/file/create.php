<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Department */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-form">

    <?php $form = ActiveForm::begin([
    	'options'=>['enctype'=>'multipart/form-data', 'id'=>'form-files-upload'],
    	'enableAjaxValidation' => false,
    ]); ?>
		
	<?php if (count($model->uploadErrors)): ?>
	<div class="alert alert-danger">
		<?php 
		foreach ($model->uploadErrors as $error)
		{
			echo $error . '<br />'; 
		}
		?>	
	</div>		
	<?php endif; ?>
	
    <?= $form->field($model, 'uploadFiles[]')->fileInput(['multiple'=>true]) ?>
   
    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success', 'id'=>'btn-submit']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




<script type="text/javascript">
	

		$('#form-files-upload').submit(function(e) {			

			e.preventDefault();
			e.stopImmediatePropagation();
			
			$('#btn-submit').prop('disabled', true);
			
			$.ajax({
				url: '<?=  Url::to(['file/create', 'id_data'=>$model->id_data]) ?>',
				type: 'POST', 
				data: new FormData(this),
				processData: false,
				contentType: false
			})
			.done(function(data) {
				$('#reestr-files-body').html(data);				
			})
			.error(function(xhr, textStatus) {
				$('#reestr-files-body').html('<div class="alert alert-danger"><h3>' + textStatus + '</h3>' + xhr.status + ' ' + xhr.statusText + '</div>');
			});
								
			return false;
		});

	

</script>