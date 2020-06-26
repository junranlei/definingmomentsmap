<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

use frontend\models\HistoricalFact;
use frontend\models\Media;
use frontend\models\Feature;


/* @var $this yii\web\View */
/* @var $searchModel app\models\FeatureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Features';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-index">
<?php
$content="
    <h1>Disabled ". Html::encode($this->title) ."</h1>

    <p>
        ".Html::a('Create Feature', ['histlistcreate','histId'=>$searchModel->histId], ['class' => 'btn btn-success']) 
    ."</p>

    ". GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            //'geojson:ntext',
            'visible',
            //'histId',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;{view}&nbsp;{enable}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', $url);
                },
                'update' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-pencil" title="Update"></span>',$url);
                },
                'enable' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-ok" title="Enable"></span>', $url,['data' => [
                        'confirm' => 'Are you sure you want to enable this item?',
                        'method' => 'post',
                    ]]);
                },
            ],
            'urlCreator' => function( $action, $model, $key, $index ){

                if ($action == "update") {

                    return Url::to(['feature/histlistupdate', 'id' => $model->id, 'histId' => $model->histId]);

                }

                if ($action == "view") {

                    return Url::to(['feature/histlistview', 'id' => $model->id, 'histId' => $model->histId]);

                }

                if ($action == "enable") {
                    return Url::to(['enable', 'id' => $model->id, 'histId' => $model->histId]);
                }

            }],
        ],
    ])."";


if(\Yii::$app->user->can("SysAdmin"))
echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/view','id'=>$searchModel->histId]),
            'active' => false,
        ],

        [

            'label' => 'Feature',
            
            'items' => [
                [
                    'label' => 'Feature',
                    'url' => Url::to(['feature/histlist','histId'=>$searchModel->histId]),
                   
                ],
                [
                    'label' => 'Disabled features',
                    'content'=>$content,
                    'active' => true, 
                    
                ],
            ]
        ],

        [
            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$searchModel->histId]),
            'active' => false,
        ],

        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$searchModel->histId]),
            'active' => false,
        ]


    ],

]);
else
echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/view','id'=>$searchModel->histId]),

        ],

        [

            'label' => 'Feature',
            'content'=>$content,
            'active' => true

        ],

        [
            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$searchModel->histId]),
        ],

        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$searchModel->histId]),
        ]


    ],

]);

?>
</div>


