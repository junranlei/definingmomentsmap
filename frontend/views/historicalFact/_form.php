<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="historicalfact-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'dateEnded')->textInput() ?>

    <?= $form->field($model, 'timeCreated')->textInput() ?>

    <?= $form->field($model, 'urls')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'mainMediaId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
