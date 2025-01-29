<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\FlagNote */

$this->title = 'Update Flag Note: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Flag Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="flag-note-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
