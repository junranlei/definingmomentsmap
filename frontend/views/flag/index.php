<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FlagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Flags';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flag-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Create Flag', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'model',
            'modelId',
            //'modelTitle',
            'times',
            'timeCreated',
            'timeUpdated',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{notes}&nbsp{view}&nbsp;{enable}',
            'buttons' => [
                
                'notes' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-list" title="Flag notes"></span>',$url, ['target' => "_blank"]);
                },
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="Flagged item"></span>', $url, ['target' => "_blank"]);
                },
                
            ],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    
                    if ($action == "notes") {

                        return Url::to(['/flagnote', 'FlagnoteSearch[flagId]' => $model->id]);

                    }
                    if ($action == "view") {

                        return Url::to(['/'.$model->model.'/view', 'id' => $model->modelId]);

                    }
                    
                }
                
            ],
        ],
    ]); ?>


</div>
