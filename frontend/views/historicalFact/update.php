<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

use frontend\models\Feature;
use frontend\models\Media;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */

$this->title = 'Update Historical fact: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Historical Fact', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="historicalfact-update">

<?php

echo Tabs::widget([

    'items' => [

        [
            'label' => 'Historical Fact',
            'content' => $this->render('_form', [
                'model' => $model,
            ]),
            'active' => true

        ],
        [

            'label' => 'Feature',
            'url' => Url::to(['feature/histlist','histId'=>$model->id]),

        ],
        [

            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$model->id]),

        ]

    ],

]);

?>
</div>
