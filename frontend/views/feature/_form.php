<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Json;

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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id'=>'Save']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// first lets setup the center of our map
    $center = new \dosamigos\leaflet\types\LatLng(['lat' => -26.117, 'lng' => 137.133]);

    // now lets create a marker that we are going to place on our map
    $marker = new \dosamigos\leaflet\layers\Marker(['latLng' => $center, 'popupContent' => 'Hi!']);

    // The Tile Layer (very important)
    $tileLayer = new \dosamigos\leaflet\layers\TileLayer([
       'urlTemplate' => 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
        'clientOptions' => [
            'attribution' => '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            //'subdomains'  => [ '1' ,'2' ,'3' ,'4' ],
        ]
    ]);

    // now our component and we are going to configure it
    $leaflet = new \dosamigos\leaflet\LeafLet([
        'center' => $center, // set the center
        'zoom'=> 4
    ]);

	// init the 2amigos leaflet plugin provided by the package
    $drawFeature = new \davidjeddy\leaflet\plugins\draw\Draw();
	// optional config array for leadlet.draw
    $drawFeature->options = [
        "position" => "topright",
        "draw" => [
            "circle" => false,
        ],
        
    ];

    if($model->geojson!=null)
        $drawFeature->existingGeojson=$model->geojson;
    else
        $drawFeature->existingGeojson='';

    // Different layers can be added to our map using the `addLayer` function.
    $leaflet->addLayer($tileLayer)          // add the tile layer          
            ->installPlugin($drawFeature);  // add draw plugin

    // we could also do
    echo $leaflet->widget(['options' => ['style' => 'min-height: 500px']]);
    ?>

