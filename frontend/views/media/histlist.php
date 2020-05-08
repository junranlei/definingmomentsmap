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
            'description:ntext',
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
           // 'nameOrUrl',
            //'histId',
            //'right2Link',
            //'ownerId',
            
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;{delete}',
            'urlCreator' => function( $action, $model, $key, $index )use ($histId){

                if ($action == "update") {

                    return Url::to(['media/histlistupdate', 'id' => $model->id, 'histId' => $histId]);

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
        ]

    ],

]);

?>
</div>

<div class="media-create">

    <h1>Create Media</h1>
    <?php $newMedia = new Media();
        //$newFeature->histId=$searchModel->histId;
    ?>
    <?= $this->render('_form', [
        'model' => $newMedia,
    ]) ?>

</div>

