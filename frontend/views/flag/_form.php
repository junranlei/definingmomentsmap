<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Flag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="flag-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modelId')->textInput() ?>

    <?= $form->field($model, 'times')->textInput() ?>

    <?= $form->field($model, 'timeCreated')->textInput() ?>

    <?= $form->field($model, 'timeUpdated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
