<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\widgets\DetailView;

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
            //'visible',
            //'mapId',
            'date',
            //'dateEnded',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{view}&nbsp;',
                'urlCreator' => function( $action, $model, $key, $index )use ($mapId){
                    if ($action == "update") {
                        return Url::to(['layer/maplistupdate', 'id' => $model->id, 'mapId' => $mapId]);

                    }
                    if ($action == "view") {
                        return Url::to(['layer/maplistview', 'id' => $model->id, 'mapId' => $mapId]);

                    }
                    /*if ($action == "delete") {
                        return Url::to(['layer/delete', 'id' => $model->id, 'mapId' => $mapId]);

                    }*/

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
            'url' => Url::to(['map/view','id'=>$mapId]),

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

<div class="layer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['disable', 'id' => $model->id, 'mapId' => $mapId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Update', ['maplistupdate', 'id' => $model->id, 'mapId' => $mapId], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            //'type',
            'nameOrUrl',
            'externalId',
            'visible',
            //'mapId',
            'date',
        ],
    ]) ?>

</div>