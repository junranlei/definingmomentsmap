<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;


$this->title = 'System management';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="Admin-index">

<h1><?= Html::encode($this->title) ?></h1>
<?php
$content="";
    echo Tabs::widget([

        'items' => [

            [
                'label' => 'API',
                'content'=>$content,
                'active' => true

            ],
            [

                'label' => 'Flag',
                'url' => Url::to(['/flag']),
                'linkOptions' => ['target'=>'_blank'],

            ],
            

        ],

    ]);

?>
</div>