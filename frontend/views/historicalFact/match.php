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

$this->title = 'Related Historical Facts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-index">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>
    <h3>Linked Manually</h3>

    ". GridView::widget([
        'dataProvider' => $dataProviderManual,
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
            'template' => '{view}&nbsp;{unlink}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', $url, ['target' => "_blank"]);
                },
                'unlink' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-link" title= "Unlink"></span>', $url,['data' => [
                        'confirm' => 'Are you sure you want to unlink this item? ',
                        'method' => 'post',
                    ]]);
                },
            ],
            'urlCreator' => function( $action, $model, $key, $index )use ($histId){

                if ($action == "view") {

                    return Url::to(['view', 'id' => $model->id]);

                } 
                if ($action == "unlink") {

                    return Url::to(['unlinkrelated', 'id' => $model->id, 'histId' => $histId]);

                }           

            }],

        ],
    ])."<h3>Linked Automatically</h3>

    ". GridView::widget([
        'dataProvider' => $dataProviderAuto,
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
            'active' => false,

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
            'items' => [
                [
                    'label' => 'Related Historical Fact',
                    'content'=>$content,
                    'active' => true, 
                ],
                [
                    'label' => 'Link to other Historical Facts',
                    'url' => Url::to(['manualmatch','id'=>$model->id]),
                    'active' => false,
                ],
            ]
        ]


    ],

]);


?>
</div>


