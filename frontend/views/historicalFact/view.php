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
    .Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ."
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
