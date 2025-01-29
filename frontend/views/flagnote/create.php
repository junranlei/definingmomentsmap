<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\FlagNote */

$this->title = 'Create Flag Note';
$this->params['breadcrumbs'][] = ['label' => 'Flag Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flag-note-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
