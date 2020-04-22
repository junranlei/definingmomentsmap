<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */

$this->title = 'Update Historicalfact: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Historicalfacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="historicalfact-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
