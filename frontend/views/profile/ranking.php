<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MapSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rankings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
<?php
 $content= '<div class="form-group"><div class="row"><div class="col-md-8" style="text-align:left;padding-left:10"><h3>'.
 Html::encode($this->title) .'</h3></div>
 <div class="col-md-4" style="text-align:right;padding-right:10"><h3>'.
 Html::button('Reset', ['class' => 'btn btn-danger','onclick'=>'window.location="'.Url::to(['profile/ranking']).'"']).
 '</h3></div></div></div>'.
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            'public_email', 
            'histCount',
            'mapCount',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}&nbsp',
            'buttons' => [
                
                'view' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>',$url, ['target' => "_blank"]);
                },
                
                
            ],
            'urlCreator' => function( $action, $model, $key, $index ){
                if ($action == "view") {

                    return Url::to(['/user/profile/show', 'id' => $model->id], ['target' => "_blank"]);

                }
                
            }
        
        ],
        ],
    ]); ?>

<?php

echo Tabs::widget([

    'items' => [
        /*[

            'label' => 'Profile',
            'url' => Url::to(['profile/show','id'=>$user->id]), 

        ],
        [

            'label' => 'Historical Facts',
            'url' => Url::to(['profile/myhists','id'=>$user->id]),

        ],*/

        [
            'label' => 'Rankings',
            'content'=>$content,
            'active' => true  
            

        ],
        

    ],

]);

?>
</div>
