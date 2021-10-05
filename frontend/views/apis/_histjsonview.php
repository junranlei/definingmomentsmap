<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Apis;
use yii\widgets\Pjax;

use slavkovrn\jsoneditor\JsonEditorWidget;


$selectfield=isset($selectfield)?$selectfield:null;
$selectapi=isset($selectapi)?$selectapi:null;



?>


      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy from API</h4>
      </div>
      <div class="modal-body">
      <?php Pjax::begin(['enablePushState' => false]); ?>
      <?= Html::beginForm(['apis/searchapi'], 'post', ['data-pjax' => '','id' => 'selectform','class' => 'form-inline']); ?>
            <?= Html::label('Select Field').' '.Html::dropDownList('selectfield',$selectfield,
            ['historicalfact-title' => 'Title', 'historicalfact-description' => 'Description',
            'historicalfact-date' => 'Date','historicalfact-urls-0' => 'URLs'
            ],
            ['id' => 'selectfield'])
             ?>
             <?= Html::label('Select API').' '.Html::dropDownList('selectapi',$selectapi,
              ArrayHelper::map(Apis::find()->orderBy("name")->all(), 'id', 'name')) ?>
              <?=  Html::hiddenInput('view', '_histjsonview') ?>
            <?= Html::input('text', 'querystring', Yii::$app->request->post('querystring'), ['size'=>'60']) ?>
            <?= Html::submitButton('Search', ['class' => 'je-val-input', 'name' => 'search-button']) ?>
            <?php if(isset($jsonlen)&&$jsonlen<=100){ echo '<br/><b>'.$jsonlen.' length data from the API indicates this search may return no results. </b>';}
            else echo ''; ?>
            <?php /* Html::input($model, 'jsonField')->widget(JsonEditorWidget::class,[
                'rootNodeName' => 'Results',
            ])->label(false)*/?>

            <?=  JsonEditorWidget::widget([
                  'model' => $apiModel,
                  'attribute' => 'jsonField',
                  'rootNodeName' => 'Results'
              ])
              
            ?>
            <?php /* $form->field($model, 'jsonField')->widget(JsonEditorWidget::class,[
                'rootNodeName' => 'Results',
            ])->label(false)*/ ?>
      <?= Html::endForm() ?>
      <?php Pjax::end(); ?>
      </div>
      <div class="modal-footer">
       <?php //echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
   