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
    Html::beginForm(['linkother','mapId'=>$mapId], 'post').
    GridView::widget([
        'dataProvider' => $dataProviderLink,
        'filterModel' => $searchModelLink,
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
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', $url, ['target' => "_blank"]);
                }
            ],
            'urlCreator' => function( $action, $model, $key, $index )use ($mapId){

                if ($action == "view") {

                    return Url::to(['view', 'id' => $model->id, 'mapId'=>$mapId]);

                }            

            }],
            ['class' => 'yii\grid\CheckboxColumn'],

        ],
    ]).Html::submitButton('Link', ['class' => 'btn btn-success', 'id' =>'link']).""
    .Html::endForm()."";


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
                    'url' => Url::to(['maplist','mapId'=>$mapId]),
                    
                ],
                [
                    'label' => 'Link to other Historical Facts',
                    'content' => $content,
                    'active' => true,
                ],
            ]

        ],

    ],

]);

?>
</div>

