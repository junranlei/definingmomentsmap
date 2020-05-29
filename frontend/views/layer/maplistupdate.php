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
$mapId = $searchModel->mapId;

?>
<div class="layer-index">
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
            'type',
            'nameOrUrl',
            'externalId',
            //'visible',
            //'mapId',
            //'date',
            //'dateEnded',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{view}&nbsp;{delete}',
                'urlCreator' => function( $action, $model, $key, $index )use ($mapId){
                    if ($action == "update") {
                        return Url::to(['layer/maplistupdate', 'id' => $model->id, 'mapId' => $mapId]);

                    }
                    if ($action == "view") {
                        return Url::to(['layer/maplistview', 'id' => $model->id, 'mapId' => $mapId]);

                    }
                    if ($action == "delete") {
                        return Url::to(['layer/delete', 'id' => $model->id, 'mapId' => $mapId]);

                    }

                }
            ],
        ],
    ]).""; 
    
?>
<?php

echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'url' => Url::to(['map/update','id'=>$mapId]),

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

<div class="layer-create">

<p>
    <?= Html::a('Create Layer', ['maplist','mapId'=>$mapId], ['class' => 'btn btn-success']) ?>
</p>

<h1>Update Layer <?= Html::encode($model->title) ?></h1>
<?= $this->render('_form', [
    'model' => $model,
]) ?>

</div>