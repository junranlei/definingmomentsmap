<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Layer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="layer-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'nameOrUrl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'externalId')->textInput(['maxlength' => true]) ?>

    <?= Html::activeLabel($model,'visible') ?>
    <?= $form->field($model, 'visible')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->visible==1)?true:false)) ?>

    <?php // $form->field($model, 'mapId')->textInput() ?>

    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        
        //'dateFormat' => 'yyyy-MM-dd',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ],
        
        
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
