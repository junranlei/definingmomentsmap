<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Layer */

$this->title = 'Create Layer';
$this->params['breadcrumbs'][] = ['label' => 'Layers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
