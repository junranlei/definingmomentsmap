<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Map */

$this->title = 'View Map: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';


?>
<?= \ymaker\social\share\widgets\SocialShare::widget([
    'configurator' => 'socialShare',
    'url'          => Yii::$app->request->absoluteUrl,
    'title'        => $model->title,
    'description'  => $model->description,
    'containerOptions'=>['tag' => 'span', 'class' => 'social-share'],
    'linkContainerOptions'=>['tag' => 'span'],
    'imageUrl'     => \yii\helpers\Url::to('absolute/route/to/image.png', true),
]); ?>
<div class="map-update">


<?php

echo Tabs::widget([

    'items' => [

        [
            'label' => 'Map',
            'content' => $this->render('_view', [
                'model' => $model,
            ]),
            'active' => true

        ],
        [

            'label' => 'Layers',
            'url' => Url::to(['layer/maplist','mapId'=>$model->id]),

        ],
        [

            'label' => 'Historical Facts',
            'url' => Url::to(['historicalfact/maplist','mapId'=>$model->id]),

        ],

    ],

]);

?>
</div>
