<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Historicalfacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="historicalfact-view">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>

    <p>
        ". Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ." ".
         Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ."
    </p>

    ". DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'date',
            'dateEnded',
            'timeCreated',
            'urls:ntext',
            'mainMediaId',
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
