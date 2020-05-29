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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Map', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
$content= '<br/>'.
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            'timeCreated',
            'timeUpdated',
            //'publicPermission',
            //'right2Add',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<?php

echo Tabs::widget([

    'items' => [

        [
            'label' => 'All Maps',
            'url' => Url::to(['map/index']),
            

        ],
        [

            'label' => 'My Maps',
            'content'=>$content,
            'active' => true

        ],
        [

            'label' => 'Assigned Maps',
            'url' => Url::to(['map/assignedmaps']),

        ],

    ],

]);

?>
</div>
