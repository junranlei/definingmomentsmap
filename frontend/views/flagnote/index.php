<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FlagnoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$flagtext="";
if($searchModel->flagId!=null)
$flagtext=' of Flag '.$searchModel->flagId;
$this->title = 'Flag Notes'.' '.$flagtext;
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="flag-note-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Create Flag Note', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'user.username',
            /*[
                'label' => 'User Name',
                'format' => 'ntext',
                'attribute'=>'username',
                'value' => function($model) {
                    return $model->user->username;
                },
            ],*/
            [
                'label' => 'User Name',
                'attribute' => 'username',
                'format' => 'raw',
                'value'=>function ($model) {
                    return Html::a(Html::encode($model->user->username), ['/user/profile/show','id'=>$model->userId], ['target'=>'_blank']);
                }
            ],
            //'flagId',
            'userId',
            'note',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
