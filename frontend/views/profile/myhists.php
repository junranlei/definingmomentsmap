<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HistoricalfactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = empty($user->profile->name) ? Html::encode($user->username) : Html::encode($user->profile->name);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['show']];
$this->params['breadcrumbs'][] = 'Historical Facts';
?>
<div class="historicalfact-index">
<?php
 $content= '<h3>'.Html::encode($this->title)."'s ".Html::encode('Historical Facts') .'</h3>
 <br/>'.
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
