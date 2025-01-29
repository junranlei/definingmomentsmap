<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use dosamigos\exportable\ExportableButton; 
use dosamigos\exportable\behaviors\ExportableBehavior; 
use dosamigos\grid\GridView;
use dosamigos\grid\behaviors\LoadingBehavior;
use dosamigos\grid\behaviors\ResizableColumnsBehavior;
use dosamigos\grid\behaviors\ToolbarBehavior;
use dosamigos\grid\buttons\ReloadButton;
use dosamigos\exportable\helpers\TypeHelper;
use frontend\controllers\DeExportableService;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HistoricalfactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = empty($user->profile->name) ? Html::encode($user->username) : Html::encode($user->profile->name);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['show']];
$this->params['breadcrumbs'][] = 'Historical Facts';
?>
<div class="historicalfact-index">
<?php
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
            'format'=>'json',
            'hide'=>['status','histId']
        ],
        [
            'label' => 'Media',
            'attribute' => 'media',
            'format'=>'json',
            'hide'=>['status','ownerId','permission2upload','right2Link','publicPermission']
        ],
        //'mainMediaId',

        //['class' => 'yii\grid\ActionColumn',
        //'template' => '{view}',
        //],
    ];
 $content= '<h3>'.Html::encode($this->title)."'s ".Html::encode('Historical Facts') .'</h3>
 <br/>'.
 \dosamigos\grid\GridView::widget([
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
            'template' => '{view}&nbsp',
            'buttons' => [
                
                'view' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>',$url, ['target' => "_blank"]);
                },
                
                
            ],
            'urlCreator' => function( $action, $model, $key, $index ){
                if ($action == "view") {

                    return Url::to(['/historicalfact/view', 'id' => $model->id], ['target' => "_blank"]);

                }
                
            }
            ],
        ],
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

echo Tabs::widget([

    'items' => [
        [

            'label' => 'Profile',
            'url' => Url::to(['profile/show','id'=>$user->id]), 

        ],
        [

            'label' => 'Historical Facts',
            'content'=>$content,
            'active' => true         

        ],

        [
            'label' => 'Maps',
            'url' => Url::to(['profile/mymaps','id'=>$user->id]),

        ],
        

    ],

]);

?>
</div>
