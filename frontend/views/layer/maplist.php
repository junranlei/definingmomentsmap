<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

use frontend\models\Layer;


/* @var $this yii\web\View */
/* @var $searchModel app\models\LayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Layers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layer-index">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>
    <p>
    ".Html::a('Create Layer', ['maplistcreate','mapId'=>$searchModel->mapId], ['class' => 'btn btn-success']).
    "</p>"
    .GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            //'type',
            'nameOrUrl',
            //'externalId',
            //'visible',
            //'mapId',
            'date',
            //'dateEnded',

            [   'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{view}',
                'urlCreator' => function( $action, $model, $key, $index )use ($mapId){
                    if ($action == "update") {
                        return Url::to(['layer/maplistupdate', 'id' => $model->id, 'mapId' => $mapId]);

                    }
                    if ($action == "view") {
                        return Url::to(['layer/maplistview', 'id' => $model->id, 'mapId' => $mapId]);

                    }

                }
            ],
        ],
    ]).""; 
    
?>
<?php
if(\Yii::$app->user->can("SysAdmin"))
echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'url' => Url::to(['map/view','id'=>$searchModel->mapId]),
            'active' => false,

        ],
        [

            'label' => 'Layers',
            
            'items' => [
                [
                    'label' => 'Layers',
                    'content'=>$content,
                    'active' => true,                    
                ],
                [
                    'label' => 'Disabled layers',
                    'url' => Url::to(['layer/disabledlist','mapId'=>$searchModel->mapId]),
                    
                ],
            ]
        ],
        [

            'label' => 'Historical Facts',
            'url' => Url::to(['historicalfact/maplist','mapId'=>$searchModel->mapId]),
            'active' => false,

        ],

    ],

]);
else
echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'url' => Url::to(['map/view','id'=>$searchModel->mapId]),

        ],
        [

            'label' => 'Layers',
            'content'=>$content,
            'active' => true

        ],
        [

            'label' => 'Historical Facts',
            'url' => Url::to(['historicalfact/maplist','mapId'=>$searchModel->mapId]),

        ],

    ],

]);

?>

</div>

