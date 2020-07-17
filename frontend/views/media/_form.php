<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\Apis;

/* @var $this yii\web\View */
/* @var $model app\models\Media */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="media-form">

    <?php $form = ActiveForm::begin(['id' => 'updateform']); ?>

    <h2 align="center">
    <?php 

        $headers = @get_headers($model->nameOrUrl); 
        $isUrl = False;
        
        // Use condition to check the existence of URL 
        if($headers && strpos( $headers[0], '200')) { 
            $isUrl=True; 
        } 
    ?>
    <?php if($model->type==1){
                if($isUrl){     
            ?>
                    <?= Html::img($model->nameOrUrl, ['width' => '400px']) ?>   
            <?php 
                }else{
            ?> 
                    <?= Html::img(Url::base().'/uploads/'.$model->id.'/'.$model->nameOrUrl, ['width' => '400px']) ?>
                    <?php 
                        }
                    ?> 
    <?php }else if($model->type==2){ 
            if($isUrl){     
            ?>
                    <?= '<video width="320" height="240" controls style="display:block; margin:0 auto;">
                    <source src="'.$model->nameOrUrl .'" type="video/mp4">
                    Your browser does not support the video tag
                    </video> ' ?>  
            <?php 
                }else{
            ?> 
                    <?= '<video width="320" height="240" controls style="display:block; margin:0 auto;">
                    <source src="'.Url::base().'/uploads/'.$model->id.'/'.$model->nameOrUrl .'" type="video/mp4">
                    Your browser does not support the video tag
                    </video> ' ?>
            <?php 
                }
            ?> 
    
    <?php  } ?>
    </h2>
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
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(
            ['1' => 'Image', '2' => 'Video']) ?>

    <?= $form->field($model, 'creator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nameOrUrl')->textInput(['maxlength' => true]) ?>

    <?= Html::activeLabel($model,'right2Link') ?>
    <?= $form->field($model, 'right2Link')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->right2Link==1)?true:false)) ?>

    <?= Html::activeLabel($model,'publicPermission') ?>
    <?= $form->field($model, 'publicPermission')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->publicPermission==1)?true:false)) ?>

    <?= Html::activeLabel($model,'isMainMedia') ?>
    <?= $form->field($model, 'isMainMedia')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checkValue'=>1, 'checked'=>($model->isMainMedia==1)?true:false)) ?>

    <?= Html::activeLabel($model,'permission2upload') ?>
    <?= $form->field($model, 'permission2upload')->checkBox(array('id'=>'permission2','label'=>'', 
    'uncheckValue'=>null,'checkValue'=>1, 'checked'=>($model->permission2upload==1)?true:false)) ?>

    <?= $form->field($model, 'files')->label("Upload File")->fileInput() ?>

    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-success','onclick' => '
        if(document.getElementById("permission2").checked)
            document.getElementById("updateform").submit();
        else
            alert("You must have the permission to publish in order to save this media.");      
        '      
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php 
        $apiModel = new Apis();
    ?>
    <div class="modal remote fade" id="modaljson">
        <div class="modal-dialog">
            <div class="modal-content loader-lg">
            <?=  $this->render('/apis/_mediajsonview', [
                'form'=>$form,
                'apiModel'=>$apiModel
            ]); ?>
            </div>
        </div>
    </div>
</div>
