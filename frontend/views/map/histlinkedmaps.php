<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MapSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Maps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="map-index">

<?php
$content="
<h1>". Html::encode($this->title) ."</h1>

    <p>
        ". Html::a('Create Map', ['create'], ['class' => 'btn btn-success']) ."
    </p>

    ". GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            'timeCreated',
            'timeUpdated',
            //'right2Add',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 

echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/view','id'=>$histId]),

        ],

        [

            'label' => 'Feature',
            'url' => Url::to(['feature/histlist','histId'=>$histId]),

        ],

        [

            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$histId]),
        ],

        [
            'label' => 'Linked Maps',
            'content'=>$content,
            'active' => true
        ]

    ],

]);

?>

</div>
