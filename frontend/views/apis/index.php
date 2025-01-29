<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ApisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'APIs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apis-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create API', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            //'description',
            'url:url',
            //'apikey',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
