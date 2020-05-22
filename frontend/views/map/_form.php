<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Map */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'timeCreated')->textInput() ?>

    <?php // $form->field($model, 'timeUpdated')->textInput() ?>

    <?= Html::activeLabel($model,'right2Add') ?>
    <?= $form->field($model, 'right2Add')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->right2Add==1)?true:false)) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
