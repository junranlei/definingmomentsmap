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

$this->title = 'Media';
$this->params['breadcrumbs'][] = $this->title;
$types=[ 1 => 'Image', 2 => 'Video' ];
?>
<div class="media-index">
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
            //'description:ntext',
            [
                'label' => 'Type',          
                'value' => function ($model, $key, $index, $column) use ($types)
                {
                    return $types[$model->type];
                }  
            ],
            [
                'attribute' => 'nameOrUrl',  
                'format' => 'raw',   
                'label' => 'Media',    
                'value' => function ($data) {
                     
                    $headers = @get_headers($data['nameOrUrl']); 
                    $isUrl = False;
                    
                    // Use condition to check the existence of URL 
                    if($headers && strpos( $headers[0], '200')) { 
                        $isUrl=True; 
                    } 
                    if($data['type']==1){
                        if($isUrl){     
                            return Html::img($data['nameOrUrl'],
                            ['width' => '80px', 'style'=>'display:block; margin:0 auto;']);
                        
                        }else{
                    
                            return Html::img(Url::base().'/uploads/'.$data['id'].'/'.$data['nameOrUrl'],
                            ['width' => '80px', 'style'=>'display:block; margin:0 auto;']); 
                        }
                               
                    }else if($data['type']==2){ 
                        if($isUrl){     
                            return '<video width="80px" height="60px" controls style="display:block; margin:0 auto;">
                            <source src="'.$data['nameOrUrl'] .'" type="video/mp4">
                            </video> ';
                        
                        }else{
                            return '<video width="80px" height="60px" controls style="display:block; margin:0 auto;">
                            <source src="'.Url::base().'/uploads/'.$data['id'].'/'.$data['nameOrUrl'] .'" type="video/mp4">
                            </video> ';
                        
                        }           
                    }   
                },
    
            ],
            //'nameOrUrl',
            //'histId',
            //'right2Link',
            //'ownerId',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;{view}&nbsp;{unlink}&nbsp;{delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', $url);
                },
                'update' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-pencil" title="Update"></span>',$url);
                },
                'unlink' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-link" title= "unlink"></span>', $url,['data' => [
                        'confirm' => 'Are you sure you want to unlink this item? ',
                        'method' => 'post',
                    ]]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash" title= "Delete"></span>', $url,['data' => [
                        'confirm' => 'Are you sure you want to delete this item? ',
                        'method' => 'post',
                    ]]);
                },
            ],
            'urlCreator' => function( $action, $model, $key, $index )use ($histId){

                if ($action == "update") {

                    return Url::to(['media/histlistupdate', 'id' => $model->id, 'histId' => $histId]);

                }
                if ($action == "view") {

                    return Url::to(['media/histlistview', 'id' => $model->id, 'histId' => $histId]);

                }
                if ($action == "unlink") {

                    return Url::to(['media/unlink', 'id' => $model->id, 'histId' => $histId]);

                }
                if ($action == "delete") {

                    return Url::to(['media/delete', 'id' => $model->id, 'histId' => $histId]);

                }

            }],
        ],
    ])."";

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/update','id'=>$histId]),
            'active' => false,
        ],
        [
            'label' => 'Feature',
            'url' => Url::to(['feature/histlist','histId'=>$histId]),
            'active' => false,
        ],
        [
            'label' => 'Media',                 
            'items' => [
                [
                    'label' => 'Create/update media',
                    'content' => $content,
                    'active' => true,
                ],
                [
                    'label' => 'Link to other media',
                    'url' => Url::to(['media/linkother','histId'=>$histId]),
                ],
            ]
        ],
        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$histId]),
            'active' => false,
        ]
    ],
]);

?>
</div>
<div class="media-create">
    <p>
        <?= Html::a('Create Media', ['histlist','histId'=>$histId], ['class' => 'btn btn-success']) ?>
    </p>
    <h1>Update Media <?= Html::encode($model->title) ?></h1>   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
