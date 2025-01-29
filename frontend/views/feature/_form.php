<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Apis;



/* @var $this yii\web\View */
/* @var $model app\models\Feature */
/* @var $form yii\widgets\ActiveForm */
?>

<?=  Html::a('Copy from API',
                    ['#','id' => $model->id], 
                    [
                        'title' => 'Copy from API',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modaljson',
                        'class' => 'btn btn-primary',
                    ]
                   );
?> 

<div class="feature-form">

    <?php $form = ActiveForm::begin(['id' => 'updateform']); ?>


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
    
    <?php 
        $apiModel = new Apis();
    ?>
    <div class="modal remote fade" id="modaljson">
        <div class="modal-dialog">
            <div class="modal-content loader-lg">
            <?=  $this->render('/apis/_featurejsonview', [
                'form'=>$form,
                'apiModel'=>$apiModel
            ]); ?>
            </div>
        </div>
    </div>

</div>
<?= $this->render('_map', [
        'model' => $model,
    ]) ?>
