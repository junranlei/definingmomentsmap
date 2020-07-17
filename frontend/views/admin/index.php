<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;


$this->title = 'System Management';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="Admin-index">

<h1><?= Html::encode($this->title) ?></h1>
<br/>
<?= Html::a('User Admin', ['/user/admin'], ['target'=>'_blank','class' => 'btn btn-primary']) ?>

<?= Html::a('Flag Reports', ['/flag'], ['target'=>'_blank','class' => 'btn btn-primary']) ?>

<?= Html::a('APIs List', ['/apis'], ['target'=>'_blank','class' => 'btn btn-primary']) ?>
<?php
/*$content="";
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
*/
?>
</div>