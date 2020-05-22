<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\grid\GridView;

use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\plugins\geocoder\ServiceNominatim;
use dosamigos\leaflet\plugins\geocoder\GeoCoder;
use dosamigos\leaflet\plugins\markercluster\MarkerCluster;
use kartik\date\DatePicker;

use frontend\models\HistoricalFact;
use frontend\models\Feature;
use frontend\models\Media;

$this->title = Yii::$app->name;
?>
<style>
.datepicker {
z-index: 1000000  !important;
}
</style>
<div class="site-index">
<?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['map'],
    ]); ?>
<div class="form-group">
    <div class="row">


    <div class="col-md-6">
    <?= $form->field($searchModel, 'keyword') ?>
    </div>
    <div class="col-md-3">
    <?= $form->field($searchModel, 'date')->widget(DatePicker::classname(), [
        
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ], 
        
    ]) ?>
    </div>
    <div class="col-md-3">
    <?=  $form->field($searchModel, 'dateEnded')->widget(DatePicker::classname(), [
        
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ],
    ])  ?>
    </div>
   </div>
   <div class="row">
        <div class="col-md-8"> </div>
                <!--<div class="col-md-offset-8 col-md-12">-->
        <div class="col-md-4" style="text-align:right;padding-right:10">
            <?= Html::submitButton('Search Historical Facts', ['class' => 'btn btn-success']) ?>
            <?= Html::button('Reset', ['class' => 'btn btn-danger','onclick'=>'window.location="'.Url::to(['site/map']).'"']) ?>
        </div>
   </div>
</div>
<?php ActiveForm::end(); ?>

</div>
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


// configure the tile layer
$tileLayer = new TileLayer([
    'urlTemplate' => 'https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'clientOptions' => [
        'attribution' => 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
        'subdomains' => '1234'
    ]
]);


// initialize our leafLet component
$leafLet = new LeafLet([
    'name' => 'geoMap',
    'tileLayer' => $tileLayer,
    'center' => $center,
    'zoom' => 2,
    'clientEvents' => [
        // I added an event to ease the collection of new position
        'geocoder_showresult' => 'function(e){
            // set markers position
            geoMarker.setLatLng(e.Result.center);
        }'
    ]
]);

$clusterPlugin = new MarkerCluster([
    'url' =>  Yii::$app->urlManager->createUrl('site/json')
]);
$clusterPlugin->setName("cluster_name");

// install to LeafLet component
$leafLet->plugins->install($clusterPlugin);

$geoCoderPlugin->setName("geocoder_name");

// install the plugin
$leafLet->installPlugin($geoCoderPlugin);

//$hists = (New Historicalfact)->find()->all();
$hists = $dataProvider->getModels();
$layersvar=[];
/*foreach($layers as $layer){
    $layersvar[$layer->title]=$layer->nameOrUrl;
}*/
$featuresvar=[];
$featureobjsvar=[];
foreach($hists as $hist){
    $features = $hist->features;
    $mainMediaId = $hist->mainMediaId;
    $mediaHtml = "";
    if($mainMediaId!=null){
        $mainMedia = Media::findOne($mainMediaId);
        if($mainMedia!=null){
            $mediaHtml = $mainMedia->getMediaUrl($width="80px", $height="60px");
        }
    }
    

    foreach($features as $feature){
        $histLink = Html::a($hist->title.'-'.$feature->title, ['historicalfact/update', 'id' => $hist->id],['target'=>'_blank']);
        $featuresvar[$hist->id][$feature->id]=$feature->geojson;
        $featureobjsvar[$hist->id][$feature->id]["geojson"]=$feature->geojson;
        $featureobjsvar[$hist->id][$feature->id]["mainId"]=$hist->mainMediaId;
        $featureobjsvar[$hist->id][$feature->id]["mediaHtml"]=Html::a($mediaHtml, ['historicalfact/update', 'id' => $hist->id],['target'=>'_blank']);
        $featureobjsvar[$hist->id][$feature->id]["histLink"]=$histLink;


        //Html::img(Url::base().'/uploads/'.$data['id'].'/'.$data['nameOrUrl'],['width' => '80px', 'style'=>'display:block; margin:0 auto;']);
    }
    
}

$this->registerJs(
    "var layersvar = ".\yii\helpers\Json::htmlEncode($layersvar).";".
    //"var featuresvar = ".\yii\helpers\Json::htmlEncode($featuresvar).";".
    //"var histsvar = ".\yii\helpers\Json::htmlEncode($hists).";".
    "var featureobjsvar = ".\yii\helpers\Json::htmlEncode($featureobjsvar).";"
    
);



$this->registerJs(<<<JS
    var mapsPlaceholder = [];

    L.Map.addInitHook(function () {
        mapsPlaceholder.push(this); // Use whatever global scope variable you like.
    });
    var basemaps2={};
    var layer1;
    var key1;
    var i=0;
    for(var key in layersvar){
        if(i==0){
            layer1 = L.tileLayer.wms(layersvar[key], {
                    layers: key
            });
            key1=key;
        }
        basemaps2[key]=L.tileLayer.wms(layersvar[key], {
                    layers: key
        });
        i++;

    }
    var basemaps = {
                Topography: L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    layers: 'TOPO-WMS'
                }),

                Places: L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    layers: 'OSM-Overlay-WMS'
                }),

                'Topography, then places': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    layers: 'TOPO-WMS,OSM-Overlay-WMS'
                }),

                'Places, then topography': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    layers: 'OSM-Overlay-WMS,TOPO-WMS'
                })
            };

    // set up the mutation observer observe when map is ready
    var observer = new MutationObserver(function (mutations, me) {
        var map2 = mapsPlaceholder.pop();
        if (map2) {
            /*if(i){
                L.control.layers(basemaps2).addTo(map2);
                basemaps2[key1].addTo(map2);
            }*/
            addCluster(map2, featureobjsvar);
            //L.control.layers(basemaps).addTo(map2);
            //basemaps.Topography.addTo(map2);
            geocoder_inputs = document.getElementsByClassName('leaflet-geocoder-input');
            for (var i = 0; i < geocoder_inputs.length; i++) {
                geocoder_inputs[i].placeholder='place name or lat,lng';
            } 
            me.disconnect(); // stop observing
            return;
        }
    });
    // start observing
    observer.observe(document, {
        childList: true,
        subtree: true
    });

    // Create a new vector type with getLatLng and setLatLng methods.
    L.PolygonClusterable = L.Polygon.extend({
        _originalInitialize: L.Polygon.prototype.initialize,

        initialize: function (latlngs, options) {
            this._originalInitialize(latlngs, options);
            this._latlng = this.getBounds().getCenter(); // Define the polygon "center".
            
        },

        getLatLng: function () {
            //this._latlng = this.getBounds().getCenter(); 
            return this._latlng;
        },

        // dummy method.
        setLatLng: function () {}
    });

    // Create a new vector type with getLatLng and setLatLng methods.
    L.PolylineClusterable = L.Polyline.extend({
        _originalInitialize: L.Polyline.prototype.initialize,

        initialize: function (latlngs, options) {
            this._originalInitialize(latlngs, options);
            this._latlng = this.getBounds().getCenter(); // Define the "center".
            //alert(this._latlng);
        },

        getLatLng: function () {
            return this._latlng;
        },

        setLatLng: function () {}
    });

    // Create a new vector type with getLatLng and setLatLng methods.
    L.MarkerClusterable = L.Marker.extend({
        _originalInitialize: L.Marker.prototype.initialize,

        initialize: function (latlng, options) {
            this._originalInitialize(latlng, options);
            this._latlng = latlng; // Define the "center".
        },

        getLatLng: function () {
            return this._latlng;
        },

        setLatLng: function () {}
    });


    function addCluster(map, features){
        markerClusterLayer = L.markerClusterGroup({
            chunkedLoading: true,
            disableClusteringAtZoom: 10
        }).addTo(map);

        
        i=0;
        for(var key1 in features){
            var hists = features[key1];
            for(var key2 in hists){
                var featureJson = hists[key2]["geojson"];
                var mediaHtml = hists[key2]["mediaHtml"];
                var histLink = hists[key2]["histLink"];
                
                //map.addLayer(featureG);
                var geojsonObject = JSON.parse(featureJson);
                var geojsonFeature = {"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"LineString","coordinates":[[-0.17754077911376956,51.52588012932929],[-0.15299320220947268,51.52545292321852]]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[131.37451171875003,-24.468483631307038]}},{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[121.37695312500001,-30.372875188118016],[118.27880859375001,-29.172627582366303],[116.80664062500001,-26.35249785815401],[118.27880859375001,-23.907265771227095],[121.81640625000001,-22.105998799750566],[124.78271484375001,-23.907265771227095],[126.56250000000001,-26.74561038219901],[124.78271484375001,-29.172627582366303],[121.37695312500001,-30.372875188118016]]]}}]};
                var geojsonLayer = new L.geoJson(geojsonObject,{

                    onEachFeature: function(feature, layer) {
                        if (feature.geometry.type === "Polygon") {
                            var clusterable = new L.PolygonClusterable(L.GeoJSON.coordsToLatLngs(feature.geometry.coordinates, 1))
                            .bindPopup(mediaHtml+histLink)
                            .addTo(markerClusterLayer);
                        }else if(feature.geometry.type === "LineString") {
                            var clusterable = new L.PolylineClusterable(L.GeoJSON.coordsToLatLngs(feature.geometry.coordinates))
                            //.bindPopup("feature:"+key2+"hist:"+key1)
                            .bindPopup(mediaHtml+histLink)
                            .addTo(markerClusterLayer);
                        }else if(feature.geometry.type === "Point") {
                            var clusterable = new L.MarkerClusterable(L.GeoJSON.coordsToLatLng(feature.geometry.coordinates))
                            .bindPopup(mediaHtml+histLink)
                            .addTo(markerClusterLayer);
                        }
                    }
                });
                
            }

        }

        
    }
    
JS
);
// run the widget (you can also use dosamigos\leaflet\widgets\Map::widget([...]))
echo $leafLet->widget(['options' => ['id'=>'map','style' => 'min-height: 500px']]);


?>
<br/><br/>
<p>
        <?= Html::a('Create Historical Fact', ['create'], ['class' => 'btn btn-success']) ?>
 </p>
<?= GridView::widget([
        'dataProvider' => $listDataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'description:ntext',
            'date',
            'dateEnded',
            //'timeCreated',
            //'urls:ntext',
            //'mainMediaId',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}&nbsp;{update}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="view historcial fact"></span>', $url, ['target' => "_blank"]);
                },
                'update' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-map-marker" title="view linked maps"></span>',$url, ['target' => "_blank"]);
                },
                        
            ],
            'urlCreator' => function( $action, $model, $key, $index ){

                if ($action == "view") {

                    return Url::to(['historicalfact/view', 'id' => $model->id], ['target' => "_blank"]);
                    

                }
                if ($action == "update") {

                    return Url::to(['map/histlinkedmaps', 'histId' => $model->id], ['target' => "_blank"]);
                    

                }

            }
            ],
        ],
    ]); ?>


</div>