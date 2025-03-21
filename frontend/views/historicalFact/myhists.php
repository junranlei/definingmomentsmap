<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HistoricalfactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historical Facts';
$this->params['breadcrumbs'][] = $this->title;
$this->params['histMenu'] = $histMenu;
?>
<div class="historicalfact-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Historical Fact', ['create'], ['class' => 'btn btn-success']) ?>
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
            'date',
            'dateEnded',
            //'timeCreated',
            //'urls:ntext',
            //'mainMediaId',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}&nbsp{update}&nbsp;',
            ],
        ],
    ]); ?>

<?php

echo Tabs::widget([

    'items' => [

        [
            'label' => 'All Historical Facts',
            'url' => Url::to(['historicalfact/index']),

        ],
        [

            'label' => 'My Historical Facts',
            'content'=>$content,
            'active' => true         

        ],
        [

            'label' => 'Assigned Historical Facts',
            'url' => Url::to(['historicalfact/assignedhists']),

        ],

    ],

]);

?>
</div>
