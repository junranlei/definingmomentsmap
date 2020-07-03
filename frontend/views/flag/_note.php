<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\Flag;
use frontend\models\FlagNote;
?>
    <?php $form = ActiveForm::begin([ 'enableClientValidation' => true,
                'options'                => [
                    'id'      => 'dynamic-form'
                 ]]);
                ?>

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add reason to flag</h4>
      </div>
      <div class="modal-body">
            <?php echo $form->field($model, 'note')->textArea(['maxlength' => true]) ?>
      </div>
      <div class="modal-footer">
       <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php ActiveForm::end(); ?>