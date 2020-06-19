<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use yii\web\JsExpression;

use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
//use yii\jui\DatePicker;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use frontend\models\User;
use frontend\models\HistoricalAssign;

/* @var $this yii\web\View */
/* @var $model frontend\models\Historicalfact */
/* @var $form yii\widgets\ActiveForm */
?>  
<?php 
$js = <<<SCRIPT
/* To initialize BS3 tooltips set this below */
$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});;
/* To initialize BS3 popovers set this below */
$(function () { 
    $("[data-toggle='popover']").popover(); 
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);
?>

<div class="historicalfact-form">

    <?php $form = ActiveForm::begin(); ?>
<?php $help = "<p class='text-justify'>Label tooltip to give some usefull information for the input. </p>";
?>

    <?= $form->field($model, 'title',['labelOptions' =>['title'=>'This is a test tooltip',
    'data-toggle'=>'tooltip',
    'class' => 'tooltipstyle']])->textInput(['maxlength' => true]) ?>
<?php //->textInput(['maxlength' => true,'placeholder'=>"title text"])?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= Html::dropDownList(
        'selectFormat', //name
        'date',  //select
        ['date'=>'Select Date below (eg, 2020-01-01)', 'months'=>'Select Month only (will include the whole month)','years'=>'Select Year only (will include the whole year)'], //items
        ['id'=>'selectFormat','onchange'=>'changeDropdown(this)'] //options
    )?>
    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        
        //'dateFormat' => 'yyyy-MM-dd',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ],
        'pluginEvents' =>[
            "changeDate" => "function(e) {  changeDateVal(e,this); }",
        ]  
        
    ]) ?>

    <?=  $form->field($model, 'dateEnded',['labelOptions' =>['title'=>'This is a test tooltip',
        'data-toggle'=>'tooltip',
        'class' => 'tooltipstyle']])->widget(DatePicker::classname(), [
        
        //'dateFormat' => 'yyyy-MM-dd',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ],
    ])  ?>
    
    <?= Html::activeLabel($model,'right2Link') ?>
    <?= $form->field($model, 'right2Link')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->right2Link==1)?true:false)) ?>

    <?= Html::activeLabel($model,'publicPermission') ?>
    <?= $form->field($model, 'publicPermission')->checkBox(array('label'=>'', 
    'uncheckValue'=>0,'checked'=>($model->publicPermission==1)?true:false)) ?>

    <?php //= $form->field($model, 'timeCreated')->textInput() ?>

    <?php 
        $users = ArrayHelper::map(User::find()->orderBy('username')->all(),'id', 'username'); 
        $musers = ArrayHelper::getColumn($model->users2,'id'); 
        if(sizeof($musers)==0)
            $tusers=[];
        else
            $tusers = explode(",",$model->getAssignedUsers());
        $model->assignedUsers = $musers;
        $url = \yii\helpers\Url::to(['historicalfact/userlist','id'=>$model->id]);
        echo $form->field($model, 'assignedUsers')->label('Assigned Users')->widget(Select2::classname(), [
            //Select2::widget([
            'id' => 'hist-assigneds',
            'name' => 'Historicalfact[assignedUsers]',
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

    <?= $form->field($model, 'urls')->widget(MultipleInput::className())
    //,['columns' =>[['name' => 'url','options' => ['placeholder'=>'url text']]]]) 
    ?>

    <?php // $form->field($model, 'mainMediaId')->textInput() ?>

    <div class="form-group">
    <?= Html::a('Delete', ['disable', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>

function changeDateVal(e,obj){

    var newDate = document.getElementById('historicalfact-date').value;
    var format = document.getElementById('selectFormat').value;
    var startDate=new Date(newDate.trim());
    var startDateString = startDate.toISOString().split('T')[0];
    var endDateString = "";
    if(format=="months"){
        var endDate = new Date(startDate.getFullYear(), startDate.getMonth()+1, 0); 
        
    }else if(format=="years"){
        var endDate = new Date(startDate.getFullYear(), 11, 31);
    }
    if(format=="months"||format=="years"){
        endDateString = (endDate.toISOString().split('T')[0]).trim();
        document.getElementById('selectFormat').value="date";
        jQuery('#historicalfact-date').kvDatepicker('destroy'); 
        jQuery('#historicalfact-date').kvDatepicker( {"autoclose": "true","viewMode": "days","minViewMode":"days","format":"yyyy-mm-dd","todayHighlight":true});
        document.getElementById('historicalfact-date').value=startDateString;
        document.getElementById('historicalfact-dateended').value=endDateString;
    }
    
}
function changeDropdown(obj){

    jQuery('#historicalfact-date').kvDatepicker('destroy'); 
    if(obj.value=="date")
        jQuery('#historicalfact-date').kvDatepicker( {"autoclose": "true","viewMode": "days","minViewMode":"days","format":"yyyy-mm-dd","todayHighlight":true});
    else if (obj.value=="months"){
        jQuery('#historicalfact-date').kvDatepicker( {"autoclose": "true","viewMode": "months","minViewMode":"months","format":"yyyy-mm"});
    }else if (obj.value=="years"){
        jQuery('#historicalfact-date').kvDatepicker( {"autoclose": "true","viewMode": "years","minViewMode":"years","format":" yyyy"});
    }
}
</script>