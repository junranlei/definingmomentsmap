<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Json;

use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\plugins\geocoder\ServiceNominatim;
use dosamigos\leaflet\plugins\geocoder\GeoCoder;



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
        <?= Html::submitButton('Save draw feature', ['class' => 'btn btn-success', 'id'=>'Save']) ?>
        <?= Html::submitButton('Save search point', ['class' => 'btn btn-success', 'id'=>'Save2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
echo '<script>var searchCentre=null;

document.getElementById("Save2").onclick = function(e) {
    if(searchCentre==null){
        alert("Please do search on the map search box first");
        return;
    }
    // Extract GeoJson from featureGroup
    var marker = L.marker(searchCentre)
    var data = marker.toGeoJSON();
    var tempjson = ['.$model->geojson.'];
    var concatGeojson = data;
    if(tempjson!=[]){
        //currentGeojson = L.geoJson(tempjson);
        //merge two geojson
        concatGeojson = concatGeoJSON(tempjson[0], data);
    }
    // Stringify the GeoJson
   
    document.getElementById("geojson").value=JSON.stringify(concatGeojson);
    
};
//merge two geojson
function concatGeoJSON(g1, g2){
    return { 
        "type" : "FeatureCollection",
        "features": g1.features.concat(g2)
    }
}

</script>';
?>

<?php
// first lets setup the center of our map
    //$center = new \dosamigos\leaflet\types\LatLng(['lat' => -26.117, 'lng' => 137.133]);

    // now lets create a marker that we are going to place on our map
    //$marker = new \dosamigos\leaflet\layers\Marker(['latLng' => $center, 'popupContent' => 'Hi!']);

    // The Tile Layer (very important)
    /*$tileLayer = new \dosamigos\leaflet\layers\TileLayer([
       'urlTemplate' => 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
        'clientOptions' => [
            'attribution' => '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            //'subdomains'  => [ '1' ,'2' ,'3' ,'4' ],
        ]
    ]);*/

    // lets use nominating service
    $nominatim = new ServiceNominatim();

    // create geocoder plugin and attach the service
    $geoCoderPlugin = new GeoCoder([
        'service' => $nominatim,
        'clientOptions' => [
            // we could leave it to allocate a marker automatically
            // but I want to have some fun
            'showMarker' => true
        ]
    ]);

    // add a marker to center
    $center = new LatLng(['lat' => -26.117, 'lng' => 137.133]);
    $marker = new Marker([
        'name' => 'geoMarker',
        'latLng' => $center,
        'clientOptions' => ['draggable' => true], // draggable marker
        'clientEvents' => [
            'dragend' => 'function(e){
                console.log(e.target._latlng.lat, e.target._latlng.lng);
            }'
        ]
    ]);

    // configure the tile layer
    $tileLayer = new TileLayer([
        'urlTemplate' => 'https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'clientOptions' => [
            'attribution' => 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
            'subdomains' => '1234'
        ]
    ]);

    // now our component and we are going to configure it
    //$leaflet = new \dosamigos\leaflet\LeafLet([
    //    'center' => $center, // set the center
    //    'zoom'=> 4
    //]);
    // initialize our leafLet component
    $leaflet = new LeafLet([
        'name' => 'geoMap',
        'tileLayer' => $tileLayer,
        'center' => $center,
        'zoom' => 4,
        'clientEvents' => [
            // I added an event to ease the collection of new position
            'geocoder_showresult' => 'function(e){
                // set markers position
                //geoMarker.setLatLng(e.Result.center);
                searchCentre = e.Result.center;
            }'
        ]
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

    $drawFeature->setName("drawfeature_name");
    $geoCoderPlugin->setName("geocoder_name");

    // Different layers can be added to our map using the `addLayer` function.
    //$leaflet->addLayer($tileLayer);          // add the tile layer   
    // add the marker
    //$leaflet->addLayer($marker);
    $leaflet->installPlugin($drawFeature);  // add draw plugin
    $leaflet->installPlugin($geoCoderPlugin); //add geocoder plugin   

    // we could also do
    echo $leaflet->widget(['options' => ['style' => 'min-height: 500px']]);
    ?>

