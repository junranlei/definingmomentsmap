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
    <h1>". Html::encode($this->title) ."</h1> <p>
    ". Html::a('Create Historical Fact', ['create'], ['class' => 'btn btn-success']) ."
</p>".
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
            'template' => '{view}&nbsp{unlink}&nbsp;',
            'buttons' => [

                'view' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>',$url, ['target' => "_blank"]);
                },
                'unlink' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-link" title= "unlink"></span>', $url,['data' => [
                        'confirm' => 'Are you sure you want to unlink this item? ',
                        'method' => 'post',
                    ]]);
                }

            ],
            'urlCreator' => function( $action, $model, $key, $index )use ($mapId){

                if ($action == "view") {

                    return Url::to(['historicalfact/view', 'id' => $model->id], ['target' => "_blank"]);

                }
                if ($action == "unlink") {

                    return Url::to(['historicalfact/unlink', 'id' => $model->id, 'mapId' => $mapId]);

                }

            }],
        ],
    ])."";


echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'url' => Url::to(['map/view','id'=>$mapId]),
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

