<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Historical Facts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="historicalfact-view">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>

    <p>
        ";
//if (\Yii::$app->user->can('updateHist',['hist' => $model])) 
    $content=$content.Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])." "
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
                        $users1links= $users1links.Html::a($user->username, ['user/profile', 'id' => $user->id], ['target' => '_blank']);
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
                        $users2links= $users2links.Html::a($user->username, ['user/profile', 'id' => $user->id], ['target' => '_blank']);
                    }
                    return $users2links;
                }
            ],
            //'right2Link',
            //'publicPermission',
            'urls:ntext',
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
        ]

    ],

]);

?>
</div>
