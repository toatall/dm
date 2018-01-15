<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Group */

$this->title = 'Создание раздела';
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>