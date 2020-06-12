<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use frontend\models\User;
use frontend\models\MapAssign;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Map */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'timeCreated')->textInput() ?>

    <?php // $form->field($model, 'timeUpdated')->textInput() ?>

    <?= Html::activeLabel($model,'right2Add') ?>
    <?= $form->field($model, 'right2Add')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->right2Add==1)?true:false)) ?>

    <?= Html::activeLabel($model,'publicPermission') ?>
    <?= $form->field($model, 'publicPermission')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->publicPermission==1)?true:false)) ?>
    
    <?php 
        $users = ArrayHelper::map(User::find()->orderBy('username')->all(),'id', 'username'); 
        $musers = ArrayHelper::getColumn($model->users2,'id'); 
        if(sizeof($musers)==0)$tusers=[];
        else
        $tusers = explode(",",$model->getAssignedUsers());
        $model->assignedUsers = $musers;
        $url = \yii\helpers\Url::to(['map/userlist','id'=>$model->id]);
        echo $form->field($model, 'assignedUsers')->label('Assigned Users')->widget(Select2::classname(), [
            //Select2::widget([
            'id' => 'map-assigneds',
            'name' => 'Map[assignedUsers]',
            'initValueText'=> $tusers,
            'value'=> $musers,
            'maintainOrder' => true,
            'options' => ['placeholder' => 'Select a user ...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => false,
                //'maximumInputLength' => 10,
                'tags' => true,
                'tokenSeparators' => [','],
                
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(user) { return user.text; }'),
                'templateSelection' => new JsExpression('function (user) { return user.text; }'),
            ],
        ]);

    ?>

    <div class="form-group">
    <?= Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
