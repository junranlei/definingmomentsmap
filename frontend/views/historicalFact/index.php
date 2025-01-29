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
use frontend\controllers\DeExportableService;

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
    $POST = Yii::$app->request->post();
    $exportColumns=[
        //['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'id',
            'attribute' => 'id',
        ],
        [
            'label' => 'Title',
            'attribute' => 'title',
        ],
        //'description:ntext',
        [
            'label' => 'URLs',
            'attribute' => 'urls',
        ],
        [
            'label' => 'Date',
            'attribute' => 'date',
        ],
        [
            'label' => 'DateEnded',
            'attribute' => 'dateEnded',
        ],
        //'timeCreated',
        //'urls:ntext',
        [
            'label' => 'Description',
            'attribute' => 'description',
        ],
        [
            'label' => 'Features',
            'attribute' => 'features',
            //'visible' => isset($POST['export'])&&$POST['export'] ? true : false,
            'format'=>'json',
            'hide'=>['status','histId']
        ],
        [
            'label' => 'Media',
            'attribute' => 'media',
            'format'=>'json',
            'hide'=>['status','ownerId','permission2upload','right2Link','publicPermission']
        ],
    ];
    $gridColumns=[
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'title',
        //'description:ntext',
        [
            'label' => 'URLs',
            'attribute' => 'urls',
            'visible' => isset($POST['export'])&&$POST['export'] ? true : false,
        ],
        'date',
        'dateEnded',
        //'timeCreated',
        //'urls:ntext',
        //'mainMediaId',

        ['class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        ],
    ];
$content= '<br/>'.
\dosamigos\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'behaviors' => [
            [
                'class' => '\dosamigos\exportable\behaviors\ExportableBehavior',
                'exportableService' => new DeExportableService(),
                'filename'=>'Defining Moments Map-'.date('d-M-Y'),
                'columns'=>$exportColumns
                //'columns'=>array()
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
                    ['label' => '<i class="glyphicon glyphicon-refresh"></i>', 'options' => ['class' => 'btn-success','onclick'=>'window.location="'.Url::to(['historicalfact/index']).'"']],
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
                                //TypeHelper::CSV => 'Export CSV <span class="label label-default">.csv</span>',               
                            
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
