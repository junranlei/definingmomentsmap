<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\HistoricalfactSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="historicalfact-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'dateEnded') ?>

    <?php // echo $form->field($model, 'timeCreated') ?>

    <?php // echo $form->field($model, 'urls') ?>

    <?php // echo $form->field($model, 'mainMediaId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
