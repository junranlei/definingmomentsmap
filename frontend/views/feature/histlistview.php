<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;

use yii\web\JsExpression;
use yii\helpers\Json;

use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\plugins\geocoder\ServiceNominatim;
use dosamigos\leaflet\plugins\geocoder\GeoCoder;

use frontend\models\HistoricalFact;
use frontend\models\Media;
use frontend\models\Feature;


/* @var $this yii\web\View */
/* @var $searchModel app\models\FeatureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Features';
$this->params['breadcrumbs'][] = $this->title;
$histId = $searchModel->histId;
?>
<div class="feature-index">
<?php
$content="
    <h1>". Html::encode($this->title) ."</h1>

    ". GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            'visible',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;{view}&nbsp;{delete}',
            'urlCreator' => function( $action, $model, $key, $index ){

                if ($action == "update") {

                    return Url::to(['feature/histlistupdate', 'id' => $model->id, 'histId' => $model->histId]);

                }
                if ($action == "view") {

                    return Url::to(['feature/histlistview', 'id' => $model->id, 'histId' => $model->histId]);

                }
                if ($action == "delete") {

                    return Url::to(['feature/delete', 'id' => $model->id, 'histId' => $model->histId]);

                }
            }],
        ],
    ])."";

echo Tabs::widget([

    'items' => [

        [

            'label' => 'Historical Fact',
            'url' => Url::to(['historicalfact/update','id'=>$histId]),

        ],

        [

            'label' => 'Feature',
            'content'=>$content,
            'active' => true

        ],

        [

            'label' => 'Media',
            'url' => Url::to(['media/histlist','histId'=>$histId]),
        ],

        [
            'label' => 'Linked Maps',
            'url' => Url::to(['map/histlinkedmaps','histId'=>$histId]),
        ]

    ],

]);

?>
</div>

<div class="feature-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['histlistupdate', 'id' => $model->id,'histId'=>$histId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id,'histId'=>$histId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'geojson:ntext',
            'visible',
            'histId',
        ],
    ]) ?>

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
    if(tempjson!=[]&&tempjson[0]!=null){
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
    $leaflet->installPlugin($drawFeature);  // add draw plugin
    $leaflet->installPlugin($geoCoderPlugin); //add geocoder plugin   

    // we could also do
    echo $leaflet->widget(['options' => ['style' => 'min-height: 500px']]);
    ?>
