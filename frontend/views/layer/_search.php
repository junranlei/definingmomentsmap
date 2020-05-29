<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LayerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="layer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'nameOrUrl') ?>

    <?= $form->field($model, 'externalId') ?>

    <?php // echo $form->field($model, 'visible') ?>

    <?php // echo $form->field($model, 'mapId') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'dateEnded') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
