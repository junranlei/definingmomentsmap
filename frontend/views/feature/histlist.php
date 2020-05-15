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
            'template' => '{update}&nbsp;{delete}',
            'urlCreator' => function( $action, $model, $key, $index ){

                if ($action == "update") {

                    return Url::to(['feature/histlistupdate', 'id' => $model->id, 'histId' => $model->histId]);

                }

                if ($action == "delete") {

                    return Url::to(['feature/delete', 'id' => $model->id, 'histId' => $model->histId]);

                }

            }],
        ],
    ])."";

echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/update','id'=>$searchModel->histId]),

        ],

        [

            'label' => 'Feature',
            'content'=>$content,
            'active' => true

        ],

        [
            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$searchModel->histId]),
        ]

    ],

]);

?>
</div>

<div class="feature-create">

    <h1>Create Feature</h1>
    <?php $newFeature = new Feature();
        $newFeature->histId=$searchModel->histId;
    ?>
    <?= $this->render('_form', [
        'model' => $newFeature,
    ]) ?>

</div>
