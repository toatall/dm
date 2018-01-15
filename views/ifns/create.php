<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ifns */

$this->title = 'Создание НО';
$this->params['breadcrumbs'][] = ['label' => 'Налоговые органы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ifns-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
