<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Tabs;
use dosamigos\exportable\ExportableButton; 
use dosamigos\exportable\behaviors\ExportableBehavior; 
use dosamigos\grid\GridView;
use dosamigos\grid\behaviors\LoadingBehavior;
use dosamigos\grid\behaviors\ResizableColumnsBehavior;
use dosamigos\grid\behaviors\ToolbarBehavior;
use dosamigos\grid\buttons\ReloadButton;
use dosamigos\exportable\helpers\TypeHelper;
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
    $POST = Yii::$app->request->post();
    $gridColumns=[
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'title',
        //'description:ntext',
        [
            'label' => 'Description',
            'attribute' => 'description',
            'visible' => isset($POST['export'])&&$POST['export'] ? true : false,
        ],
        'timeCreated',
        'timeUpdated',
        //'right2Add',

        ['class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        ],
    ];
    $exportColumns=[
        //['class' => 'yii\grid\SerialColumn'],

        'id',
        'title',
        'description:ntext',
        'timeCreated',
        'timeUpdated',
        //'right2Add',

        //['class' => 'yii\grid\ActionColumn',
        //'template' => '{view}',
        //],
    ];

$content= '<br/>'.

 \dosamigos\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'behaviors' => [
            [
                'class' => '\dosamigos\exportable\behaviors\ExportableBehavior',
                'filename'=>'Defining Moments Map-'.date('d-M-Y')
            ],
            [
                'class' => '\dosamigos\grid\behaviors\LoadingBehavior',
                'type' => 'bars'
            ],
            [
                'class' => '\dosamigos\grid\behaviors\ToolbarBehavior',
                'options' => ['style' => 'margin-bottom: 5px'],
                'encodeLabels' => false, // like this we will be able to display HTML on our buttons
                'buttons' => [
                    ['label' => '<i class="glyphicon glyphicon-refresh"></i>', 'options' => ['class' => 'btn-success','onclick'=>'window.location="'.Url::to(['map/index']).'"']],
                    //ReloadButton::widget(['options' => ['class' => 'btn-success']]),
                    '-',
                    ExportableButton::widget(
                        [
                            'label' => '<i class="glyphicon glyphicon-export"></i>',
                            'options' => ['class' => 'btn-default'],
                            //'url' => Url::to(['export/export']), 
                            'dropdown' => [
                                'options' => ['class' => 'dropdown-menu-right']
                            ],
                            'types' =>[
                                TypeHelper::JSON => 'Export JSON <span class="label label-default">.json</span>',
                                TypeHelper::CSV => 'Export CSV <span class="label label-default">.csv</span>',               
                            
                            ]
                        ]
                    )
                ]
            ]

        ],
        'layout' => "{toolbar}\n{summary}\n{items}\n{pager}",
    ]); ?>

<?php
if(\Yii::$app->user->can("SysAdmin"))
echo Tabs::widget([

    'items' => [

        [
            'label' => 'All Maps',
            'content'=>$content,
            'active' => true

        ],
        [

            'label' => 'My Maps',
            'url' => Url::to(['map/mymaps']),

        ],
        [

            'label' => 'Assigned Maps',
            'url' => Url::to(['map/assignedmaps']),

        ],
        [

            'label' => 'Disabled Maps',
            'url' => Url::to(['map/disabledmaps']),

        ]

    ],

]);
else
echo Tabs::widget([

    'items' => [

        [
            'label' => 'All Maps',
            'content'=>$content,
            'active' => true

        ],
        [

            'label' => 'My Maps',
            'url' => Url::to(['map/mymaps']),

        ],
        [

            'label' => 'Assigned Maps',
            'url' => Url::to(['map/assignedmaps']),

        ],

    ],

]);

?>
</div>
