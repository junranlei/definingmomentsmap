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
            //'visible',
            //'mapId',
            //'date',
            //'dateEnded',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]).""; 
    
?>
<?php

echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'url' => Url::to(['map/update','id'=>$searchModel->mapId]),

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

<h1>Create Layer</h1>
<?php $newLayer = new Layer();
        $newLayer->mapId=$searchModel->mapId;
    ?>
<?= $this->render('_form', [
    'model' => $newLayer,
]) ?>

</div>