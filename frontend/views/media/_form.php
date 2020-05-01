<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Media */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="media-form">

    <?php $form = ActiveForm::begin(); ?>

    <h2 align="center">
    <?php if($model->type==1){?>
    <?= Html::img(Url::base().'/uploads/'.$model->id.'/'.$model->nameOrUrl, ['width' => '400px']) ?>
    <?php }else if($model->type==2){ ?>
    <?= '<video width="320" height="240" controls style="display:block; margin:0 auto;">
        <source src="'.Url::base().'/uploads/'.$model->id.'/'.$model->nameOrUrl .'" type="video/mp4">
        Your browser does not support the video tag
        </video> ' ?>
    <?php  } ?>
    </h2>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList(
            ['1' => 'Image', '2' => 'Video']) ?>

    <?= $form->field($model, 'nameOrUrl')->textInput(['maxlength' => true]) ?>

    <?= Html::activeLabel($model,'right2Link') ?>
    <?= $form->field($model, 'right2Link')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->right2Link==1)?true:false)) ?>

    <?= $form->field($model, 'files')->label("Upload File")->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
