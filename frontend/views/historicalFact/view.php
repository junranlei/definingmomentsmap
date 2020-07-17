<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\HistoricalFact */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Historical Facts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="modal remote fade" id="modalflag">
        <div class="modal-dialog">
            <div class="modal-content loader-lg"></div>
        </div>
</div>
<div class="historicalfact-view">


<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>

    <p>
        ";
//if (\Yii::$app->user->can('updateHist',['hist' => $model])) 
    $content=$content.Html::a('Flag',
    ['/flag/flagmap','id' => $model->id,'m'=>'historicalfact'], 
    [
        'title' => 'Flag',
        'data-toggle'=>'modal',
        'data-target'=>'#modalflag',
        'class' => 'btn btn-danger',
    ]
   )." ".Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])." "
    ."
     </p>";

    $content=$content. DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'date',
            'dateEnded',
            'timeCreated',
            'timeUpdated',
            [
                'attribute'=>'Owner',
                'format'=>'raw',
                'value'=>function ($model)
                {
                    //return implode(', ', \yii\helpers\ArrayHelper::map($model->users1, 'id', 'username'));
                    $users1=$model->users1;
                    $users1links="";
                    foreach($users1 as $user){
                        if($users1links!="")
                            $users1links=$users1links.",";
                        $users1links= $users1links.Html::a($user->username, ['user/profile/show', 'id' => $user->id], ['target' => '_blank']);
                    }
                    return $users1links;
                }
            ],
            [
                'attribute'=>'Assigned',
                'format'=>'raw',
                'value'=>function ($model)
                {
                    //return implode(', ', \yii\helpers\ArrayHelper::map($model->users1, 'id', 'username'));
                    $users2=$model->users2;
                    $users2links="";
                    foreach($users2 as $user){
                        if($users2links!="")
                            $users2links=$users2links.", ";
                        $users2links= $users2links.Html::a($user->username, ['user/profile/show', 'id' => $user->id], ['target' => '_blank']);
                    }
                    return $users2links;
                }
            ],
            //'right2Link',
            //'publicPermission',
            [
                'attribute'=>'urls',
                'format'=>'raw',
                'value'=>function ($model)
                {
                    //return implode(', ', \yii\helpers\ArrayHelper::map($model->users1, 'id', 'username'));
                    $urls=$model->urls;
                    $urlslinks="";
                    $urlsA = explode(";",$model->urls);
                    foreach($urlsA as $url){
                        if($urlslinks!="")
                            $urlslinks=$urlslinks."; ";
                        $urlslinks= $urlslinks.Html::a($url, $url, ['target' => '_blank']);
                    }
                    return $urlslinks;
                }
            ],
            //'urls:ntext',
            //'mainMediaId',
        ],
    ])."";

echo Tabs::widget([

    'items' => [

        [
            'label' => 'Historical Fact',
            'content' => $content,
            'active' => true

        ],
        [

            'label' => 'Feature',
            'url' => Url::to(['feature/histlist','histId'=>$model->id]),

        ],
        [

            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$model->id]),

        ],

        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$model->id]),
        ],

        [
            'label' => 'Related Historical Fact',
            'url' => Url::to(['match','id'=>$model->id]),
        ]

    ],

]);

?>
</div>
