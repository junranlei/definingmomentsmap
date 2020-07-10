<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\helpers\Url;

use frontend\models\Media;

use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\plugins\geocoder\ServiceNominatim;
use dosamigos\leaflet\plugins\geocoder\GeoCoder;
use dosamigos\leaflet\plugins\markercluster\MarkerCluster;



/* @var $this yii\web\View */
/* @var $model app\models\Map */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="modal remote fade" id="modalflag">
        <div class="modal-dialog">
            <div class="modal-content loader-lg"></div>
        </div>
</div>
<div class="map-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>  
    <?=  Html::a('Flag',
                    ['/flag/flagmap','id' => $model->id,'m'=>'map'], 
                    [
                        'title' => 'Flag',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modalflag',
                        'class' => 'btn btn-danger',
                    ]
                   );
?>    
        
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'timeCreated',
            'timeUpdated',
            [
                'attribute'=>'Owner',
                'format'=>'raw',
                'value'=>function ($model)
                {
                    //return implode(', ', \yii\helpers\ArrayHelper::map($model->users1, 'id', 'username'));
                    $users1=$model->users1;
                    $users1links="";
                    foreach($users1 as $user){
                        if($users1links!="")
                            $users1links=$users1links.",";
                        $users1links= $users1links.Html::a($user->username, ['user/profile/show', 'id' => $user->id], ['target' => '_blank']);
                    }
                    return $users1links;
                }
            ],
            [
                'attribute'=>'Assigned',
                'format'=>'raw',
                'value'=>function ($model)
                {
                    //return implode(', ', \yii\helpers\ArrayHelper::map($model->users1, 'id', 'username'));
                    $users2=$model->users2;
                    $users2links="";
                    foreach($users2 as $user){
                        if($users2links!="")
                            $users2links=$users2links.", ";
                        $users2links= $users2links.Html::a($user->username, ['user/profile/show', 'id' => $user->id], ['target' => '_blank']);
                    }
                    return $users2links;
                }
            ],
            //'publicPermission'
        ],
    ]) ?>

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

//Get data
$layers = $model->layers;
$hists = $model->hists;
$layersvar=[];
$n=0;
foreach($layers as $layer){
    if($layer->visible==1){
        $layersvar[$n]["url"]=trim($layer->nameOrUrl);
        $layersvar[$n]["layername"]=trim($layer->externalId);
        $layersvar[$n]["displaytitle"]=trim($layer->title);
        $n++;
    }
    //$layersvar[trim($layer->title)]=trim($layer->nameOrUrl);
}
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
        if($feature->visible==1){
            $histLink = Html::a($hist->title.'-'.$feature->title, ['historicalfact/view', 'id' => $hist->id],['target'=>'_blank']);
            $featuresvar[$hist->id][$feature->id]=$feature->geojson;
            $featureobjsvar[$hist->id][$feature->id]["geojson"]=$feature->geojson;
            $featureobjsvar[$hist->id][$feature->id]["mainId"]=$hist->mainMediaId;
            $featureobjsvar[$hist->id][$feature->id]["mediaHtml"]=Html::a($mediaHtml, ['historicalfact/view', 'id' => $hist->id],['target'=>'_blank']);
            $featureobjsvar[$hist->id][$feature->id]["histLink"]=$histLink;
        }

        //Html::img(Url::base().'/uploads/'.$data['id'].'/'.$data['nameOrUrl'],['width' => '80px', 'style'=>'display:block; margin:0 auto;']);
    }
    
}
// prepare JS variable 
$this->registerJs(
    "var layersvar = ".\yii\helpers\Json::htmlEncode($layersvar).";".
    "var featuresvar = ".\yii\helpers\Json::htmlEncode($featuresvar).";".
    "var histsvar = ".\yii\helpers\Json::htmlEncode($hists).";".
    "var featureobjsvar = ".\yii\helpers\Json::htmlEncode($featureobjsvar).";"
    
);


// creater JS 
$this->registerJs(<<<JS
//alert(JSON.stringify(featureobjsvar));alert(histsvar[0]["id"]);
    var mapsPlaceholder = [];

    L.Map.addInitHook(function () {
        mapsPlaceholder.push(this); // Use whatever global scope variable you like.
    });
    var basemaps2={};
    var layer1;
    var key1;
    var j=0;
    for(var key in layersvar){
        if(j==0){
            
            layer1 = L.tileLayer.wms(layersvar[key]["url"], {
                layers: layersvar[key]["layername"]
            });
            key1=key;
            basemaps2[layersvar[key]["displaytitle"]] = layer1;
        }else{
            basemaps2[layersvar[key]["displaytitle"]]=L.tileLayer.wms(layersvar[key]["url"], {
                layers: layersvar[key]["layername"]
            });
        }
        /*basemaps2[key]=L.tileLayer.wms(layersvar[key], {
                    layers: key
        });*/
        j++;

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
            if(j){
                L.control.layers(basemaps2).addTo(map2);
                layer1.addTo(map2);
            }
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
            //alert(this._latlng);
        },

        getLatLng: function () {//alert(this._latlng);
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

</div>

<?php 

?>