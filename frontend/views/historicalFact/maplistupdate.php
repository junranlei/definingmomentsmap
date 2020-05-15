<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

use frontend\models\historicalfact;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HistoricalfactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historical Facts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historicalfact-index">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>".
GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            'date',
            'dateEnded',
            //'timeCreated',
            //'urls:ntext',
            //'mainMediaId',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}&nbsp;{update}&nbsp;{delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil" title="Update"></span>', $url);
                },
                'update' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-map-marker" title="Update more"></span>',$url, ['target' => "_blank"]);
                },
                        'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash" title= "Delete"></span>', $url);
                },
            ],
            'urlCreator' => function( $action, $model, $key, $index ) use ($mapId){

                if ($action == "view") {

                    return Url::to(['maplistupdate', 'id' => $model->id, 'mapId'=>$mapId]);

                }

                if ($action == "update") {

                    return Url::to(['historicalfact/update', 'id' => $model->id], ['target' => "_blank"]);

                }

                if ($action == "delete") {

                    return Url::to(['delete', 'id' => $model->id]);

                }

            }],
            
        ],
    ])."";


echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'url' => Url::to(['map/update','id'=>$mapId]),
            'active' => false,

        ],
        [

            'label' => 'Layers',
            'url' => Url::to(['layer/maplist','mapId'=>$mapId]),
            'active' => false,

        ],
        [

            'label' => 'Historical Facts',            
            'items' => [
                [
                    'label' => 'Create/update Historical Facts',
                    'content'=>$content,
                    'active' => true
                    
                ],
                [
                    'label' => 'Link to other Historical Facts',
                    'url' => Url::to(['linkother','mapId'=>$mapId]),
                ],
            ]

        ],

    ],

]);

?>
</div>

<div class="historicalfact-create">
<p>
        <?= Html::a('Create Historical Fact', ['maplist','mapId'=>$mapId], ['class' => 'btn btn-success']) ?>
    </p>
<h1>Update Historical Fact <?= Html::encode($model->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>