<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use slavkovrn\jsoneditor\JsonEditorWidget;
?>


      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search API</h4>
      </div>
      <div class="modal-body">
            <?= $form->field($model, 'jsonField')->widget(JsonEditorWidget::class,[
                'rootNodeName' => 'Results',
            ])?>
      </div>
      <div class="modal-footer">
       <?php //echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
   