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
$histId = $searchModel->histId;
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
            'description:ntext',
            'visible',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
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
            'url' => Url::to(['historicalfact/update','id'=>$histId]),

        ],

        [

            'label' => 'Feature',
            'content'=>$content,
            'active' => true

        ],

        [

            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$histId]),
        ]

    ],

]);

?>
</div>

<div class="feature-create">
    <p>
        <?= Html::a('Create Feature', ['histlist','histId'=>$histId], ['class' => 'btn btn-success']) ?>
    </p>

    <h1>Update Feature <?= Html::encode($model->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
