<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;




/* @var $this yii\web\View */
/* @var $model app\models\Feature */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feature-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= Html::activeLabel($model,'visible') ?>
    <?= $form->field($model, 'visible')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->visible==1)?true:false)) ?>

    <?= $form->field($model, 'histId')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'geojson')->hiddenInput(['id'=>'geojson'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save draw feature', ['class' => 'btn btn-success', 'id'=>'Save']) ?>
        <?= Html::submitButton('Save search point', ['class' => 'btn btn-success', 'id'=>'Save2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?= $this->render('_map', [
        'model' => $model,
    ]) ?>
