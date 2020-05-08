<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;

use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */
/* @var $form yii\widgets\ActiveForm */
?>  

<div class="historicalfact-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date')->widget(DateControl::classname(), [
        
        'displayFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'dateEnded')->widget(DateControl::classname(), [
        
        'displayFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?php //= $form->field($model, 'timeCreated')->textInput() ?>

    <?= $form->field($model, 'urls')->widget(MultipleInput::className()) ?>

    <?= $form->field($model, 'mainMediaId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
