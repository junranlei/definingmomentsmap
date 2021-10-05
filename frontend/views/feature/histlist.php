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
    <h1>". Html::encode($this->title) ."</h1>

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
            //'visible',
            [
                'format' => 'boolean',           
                'attribute' => 'visible',             
                'filter' => [0=>'No',1=>'Yes'],             
            ],
            //'histId',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;{view}&nbsp;',
            'urlCreator' => function( $action, $model, $key, $index ){

                if ($action == "update") {

                    return Url::to(['feature/histlistupdate', 'id' => $model->id, 'histId' => $model->histId]);

                }

                if ($action == "view") {

                    return Url::to(['feature/histlistview', 'id' => $model->id, 'histId' => $model->histId]);

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
                    'content'=>$content,
                    'active' => true,                    
                ],
                [
                    'label' => 'Disabled features',
                    'url' => Url::to(['feature/disabledlist','histId'=>$searchModel->histId]),
                    
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
        ],

        [
            'label' => 'Related Historical Fact',
            'url' => Url::to(['/historicalfact/match','id'=>$searchModel->histId]),
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


