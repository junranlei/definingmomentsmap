<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;



use frontend\models\HistoricalFact;
use frontend\models\Media;
use frontend\models\Feature;


/* @var $this yii\web\View */
/* @var $searchModel app\models\FeatureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Features';
$this->params['breadcrumbs'][] = $this->title;
$histId = $searchModel->histId;
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
             //'visible',
             [
                'format' => 'boolean',           
                'attribute' => 'visible',             
                'filter' => [0=>'No',1=>'Yes'],             
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;{view}&nbsp',
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

echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/view','id'=>$histId]),

        ],

        [

            'label' => 'Feature',
            'content'=>$content,
            'active' => true

        ],

        [

            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$histId]),
        ],

        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$histId]),
        ]

    ],

]);

?>
</div>

<div class="feature-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a('Delete', ['disable', 'id' => $model->id,'histId'=>$histId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Update', ['histlistupdate', 'id' => $model->id,'histId'=>$histId], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            //'geojson:ntext',
            'visible',
            //'histId',
        ],
    ]) ?>

</div>


<?= $this->render('_map', [
        'model' => $model,
    ]) ?>