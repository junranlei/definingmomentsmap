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
            'template' => '{view}&nbsp{update}&nbsp;{enable}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', $url);
                },
                'update' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-pencil" title="Update"></span>',$url);
                },
                'enable' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-ok" title="Enable"></span>', $url,['data' => [
                        'confirm' => 'Are you sure you want to enable this item?',
                        'method' => 'post',
                    ]]);
                },
            ],
            'urlCreator' => function( $action, $model, $key, $index ) {
                if ($action == "view") {

                    return Url::to(['view', 'id' => $model->id]);

                }
                if ($action == "update") {

                    return Url::to(['update', 'id' => $model->id]);

                }
                if ($action == "enable") {
                    return Url::to(['enable', 'id' => $model->id]);
                }
            }]
        ],
    ]); ?>

<?php
if(\Yii::$app->user->can("SysAdmin"))
    echo Tabs::widget([

        'items' => [

            [
                'label' => 'All Historical Facts',
                'url' => Url::to(['historicalfact/index']),


            ],
            [

                'label' => 'My Historical Facts',
                'url' => Url::to(['historicalfact/myhists']),

            ],
            [

                'label' => 'Assigned Historical Facts',
                'url' => Url::to(['historicalfact/assignedhists']),

            ],
            [

                'label' => 'Disabled Historical Facts',
                'content'=>$content,
                'active' => true

            ]

        ],

    ]);



?>
</div>
