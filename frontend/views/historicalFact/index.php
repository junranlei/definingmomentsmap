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
$this->params['histMenu'] = True;
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
            'template' => '{view}',
            ],
        ],
    ]); ?>

<?php
if(\Yii::$app->user->can("SysAdmin"))
    echo Tabs::widget([

        'items' => [

            [
                'label' => 'All Historical Facts',
                'content'=>$content,
                'active' => true

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
                'url' => Url::to(['historicalfact/disabledhists']),

            ]

        ],

    ]);

else

echo Tabs::widget([

    'items' => [

        [
            'label' => 'All Historical Facts',
            'content'=>$content,
            'active' => true

        ],
        [

            'label' => 'My Historical Facts',
            'url' => Url::to(['historicalfact/myhists']),

        ],
        [

            'label' => 'Assigned Historical Facts',
            'url' => Url::to(['historicalfact/assignedhists']),

        ],

    ],

]);

?>
</div>
