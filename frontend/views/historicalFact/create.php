<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

use frontend\models\Feature;
use frontend\models\Media;

/* @var $this yii\web\View */
/* @var $model frontend\models\HistoricalFact */

$this->title = 'Create HistoricalFact';
$this->params['breadcrumbs'][] = ['label' => 'HistoricalFacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historicalfact-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
