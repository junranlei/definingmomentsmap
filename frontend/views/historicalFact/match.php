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

$this->title = 'Historical Facts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-index">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>

    ". GridView::widget([
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
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', $url, ['target' => "_blank"]);
                }
            ],
            'urlCreator' => function( $action, $model, $key, $index ){

                if ($action == "view") {

                    return Url::to(['view', 'id' => $model->id]);

                }            

            }],

        ],
    ])."";



echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/view','id'=>$model->id]),
            'active' => false,
        ],

        [

            'label' => 'Feature',
            'url' => Url::to(['feature/histlist','histId'=>$model->id]),

        ],

        [
            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$model->id]),
            'active' => false,
        ],

        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$model->id]),
            'active' => false,
        ],

        [
            'label' => 'Related Historical Fact',
            'content'=>$content,
            'active' => true, 
        ]


    ],

]);


?>
</div>


