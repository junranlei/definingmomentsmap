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
use dosamigos\leaflet\layers\Circle;
use dosamigos\leaflet\types\Icon;
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
    'zoom' => 4,
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

//Get Data
//$hists = (New HistoricalFact)->find()->all();
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
        if($feature->visible==1){
            //$histLink = Html::a($hist->title.'-'.$feature->title, ['historicalfact/view', 'id' => $hist->id],['target'=>'_blank']);
            $histLink = Html::a('<b>'.$hist->title.'</b>', ['historicalfact/view', 'id' => $hist->id],['target'=>'_blank']);
            $featuresvar[$hist->id][$feature->id]=$feature->geojson;
            $featureobjsvar[$hist->id][$feature->id]["geojson"]=$feature->geojson;
            $featureobjsvar[$hist->id][$feature->id]["mainId"]=$hist->mainMediaId;
            $featureobjsvar[$hist->id][$feature->id]["mediaHtml"]=Html::a($mediaHtml, ['historicalfact/view', 'id' => $hist->id],['target'=>'_blank']);
            $featureobjsvar[$hist->id][$feature->id]["histLink"]=$histLink;
            $des = $hist->description;
            $des = (strlen($des) > 128) ? substr($des,0,125).'...' : $des;
            $featureobjsvar[$hist->id][$feature->id]["histDes"]=$des;
            $featureobjsvar[$hist->id][$feature->id]["histDate"]=$hist->date;
        }

    }
    
}
//Prepare JS variable
$this->registerJs(
    "var layersvar = ".\yii\helpers\Json::htmlEncode($layersvar).";".
    //"var featuresvar = ".\yii\helpers\Json::htmlEncode($featuresvar).";".
    //"var histsvar = ".\yii\helpers\Json::htmlEncode($hists).";".
    "var featureobjsvar = ".\yii\helpers\Json::htmlEncode($featureobjsvar).";"
    
);


//Create JS
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
                Default: L.tileLayer.wms('http://a.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png',{
                    attribution: '© <a href="https://carto.com">CARTO</a>',
                }),
                'OpenStreetMap': L.tileLayer.wms('https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'),
                //'3D Terrain': L.tileLayer.wms('https://api.maptiler.com/tiles/terrain-quantized-mesh/{z}/{x}/{y}.terrain?key=iOrdi9iZBBMPWCxdvMS9'),
                'Google Street': L.tileLayer.wms("http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}",{
                    attribution: '© <a href="https://www.google.com/maps">Google Maps</a>',
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }),
                'Google Hybrid': L.tileLayer.wms('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
                    attribution: '© <a href="https://www.google.com/maps">Google Maps</a>',
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }),
                'Google Satellite': L.tileLayer.wms("http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",{
                    attribution: '© <a href="https://www.google.com/maps">Google Maps</a>',
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }),
                'Google Terrain': L.tileLayer.wms("http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}",{
                    attribution: '© <a href="https://www.google.com/maps">Google Maps</a>',
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }),
                /*'Maptiler Hybrid': L.tileLayer.wms('https://api.maptiler.com/maps/hybrid/256/{z}/{x}/{y}.jpg?key=iOrdi9iZBBMPWCxdvMS9',{
                    attribution: '© <a href="maptiler.com">Maptiler.com</a>',
                    styles: 'https://api.maptiler.com/maps/d70e253b-11d4-42dc-b643-a6b8a3a6ed74/style.json?key=iOrdi9iZBBMPWCxdvMS9'
                }),

                'Maptiler Basic': L.tileLayer.wms('https://api.maptiler.com/maps/basic/256/{z}/{x}/{y}.png?key=iOrdi9iZBBMPWCxdvMS9',{
                    attribution: '© <a href="maptiler.com">Maptiler.com</a>',
                    styles: 'https://api.maptiler.com/maps/d70e253b-11d4-42dc-b643-a6b8a3a6ed74/style.json?key=iOrdi9iZBBMPWCxdvMS9'
                }),

                'Mapbox Street': L.tileLayer.wms('https://api.mapbox.com/styles/v1/mapbox/{mapId}/tiles/{z}/{x}/{y}?access_token={token}#2/-26.117/137.133', {
                    attribution: '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    mapId: 'streets-v11',
                    token: 'pk.eyJ1IjoianVucmFuIiwiYSI6ImNrZG8zYmJqYTFvYXEycGxqdWdqcG41NzMifQ.ZQZDbWSAe-h88n7uVz5kIw'
                }),
                'Mapbox Satellite': L.tileLayer.wms('https://api.mapbox.com/styles/v1/mapbox/{mapId}/tiles/{z}/{x}/{y}?access_token={token}#2/-26.117/137.133', {
                    attribution: '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    mapId: 'satellite-streets-v11',
                    token: 'pk.eyJ1IjoianVucmFuIiwiYSI6ImNrZG8zYmJqYTFvYXEycGxqdWdqcG41NzMifQ.ZQZDbWSAe-h88n7uVz5kIw'
                }),
                'Mapbox Light': L.tileLayer.wms('https://api.mapbox.com/styles/v1/mapbox/{mapId}/tiles/{z}/{x}/{y}?access_token={token}#2/-26.117/137.133', {
                    attribution: '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    mapId: 'light-v10',
                    token: 'pk.eyJ1IjoianVucmFuIiwiYSI6ImNrZG8zYmJqYTFvYXEycGxqdWdqcG41NzMifQ.ZQZDbWSAe-h88n7uVz5kIw'
                }),
                'Mapbox Dark': L.tileLayer.wms('https://api.mapbox.com/styles/v1/mapbox/{mapId}/tiles/{z}/{x}/{y}?access_token={token}#2/-26.117/137.133', {
                    attribution: '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    mapId: 'dark-v10',
                    token: 'pk.eyJ1IjoianVucmFuIiwiYSI6ImNrZG8zYmJqYTFvYXEycGxqdWdqcG41NzMifQ.ZQZDbWSAe-h88n7uVz5kIw'
                }),
                'Mapbox Outdoor': L.tileLayer.wms('https://api.mapbox.com/styles/v1/mapbox/{mapId}/tiles/{z}/{x}/{y}?access_token={token}#2/-26.117/137.133', {
                    attribution: '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    mapId: 'outdoors-v11',
                    token: 'pk.eyJ1IjoianVucmFuIiwiYSI6ImNrZG8zYmJqYTFvYXEycGxqdWdqcG41NzMifQ.ZQZDbWSAe-h88n7uVz5kIw'
                }),*/
                'Watercolor': L.tileLayer.wms('http://a.tile.stamen.com/watercolor/{z}/{x}/{y}.png',{
                    attribution: '© <a href="https://stamen.com">Stamen.com</a>',
                }),
                'Toner': L.tileLayer.wms('http://a.tile.stamen.com/toner/{z}/{x}/{y}.png',{
                    attribution: '© <a href="https://stamen.com">stamen.com</a>',
                }),
                'Carto Dark': L.tileLayer.wms('http://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png',{
                    attribution: '© <a href="https://carto.com">CARTO</a>',
                }),
                'Carto Light': L.tileLayer.wms('http://a.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png',{
                    attribution: '© <a href="https://carto.com">CARTO</a>',
                }),
                'Carto Antique': L.tileLayer.wms('https://cartocdn_{s}.global.ssl.fastly.net/base-antique/{z}/{x}/{y}.png',{
                    attribution: '© <a href="https://carto.com">CARTO</a>',
                }),
                Topography: L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    attribution: '© <a href="https://mundialis.de">Mundialis.de</a>',
                    layers: 'TOPO-WMS'
                }),
                
                'Places': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    attribution: '© <a href="https://mundialis.de">Mundialis.de</a>',
                    layers: 'OSM-Overlay-WMS'
                }),

                'Topography & places': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    attribution: '© <a href="https://mundialis.de">Mundialis.de</a>',
                    layers: 'TOPO-WMS,OSM-Overlay-WMS'
                }),

                /*'Places, then topography': L.tileLayer.wms('http://ows.mundialis.de/services/service?', {
                    layers: 'OSM-Overlay-WMS,TOPO-WMS'
                })*/
            };
    var randomLayer = function (obj) {
        var keys = Object.keys(obj);
        return obj[keys[ keys.length * Math.random() << 0]];
    };

    // set up the mutation observer observe when map is ready
    var observer = new MutationObserver(function (mutations, me) {
        var map2 = mapsPlaceholder.pop();
        if (map2) {
            //default layers
            /*if(i){
                L.control.layers(basemaps2).addTo(map2);
                basemaps2[key1].addTo(map2);
            }*/
            addCluster(map2, featureobjsvar);
            L.control.layers(basemaps).addTo(map2);
            randomLayer(basemaps).addTo(map2);
            //placeholder on map search bar
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
        },

        getLatLng: function () {
            return this._latlng;
        },

        setLatLng: function () {}
    });
//svg icon
    var svg = '<svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 149 178"><path fill="{mapIconColor}" stroke="#FFF" stroke-width="6" stroke-miterlimit="10" d="M126 23l-6-6A69 69 0 0 0 74 1a69 69 0 0 0-51 22A70 70 0 0 0 1 74c0 21 7 38 22 52l43 47c6 6 11 6 16 0l48-51c12-13 18-29 18-48 0-20-8-37-22-51z"/><circle fill="{mapIconColorInnerCircle}" cx="74" cy="75" r="61"/><circle fill="#FFF" cx="74" cy="75" r="{pinInnerCircleRadius}"/></svg>'; /* insert your own svg */
    var svg2='<?xml version="1.0" ?><svg height="24" version="1.1" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"><g transform="translate(0 -1028.4)"><path d="m12 0c-4.4183 2.3685e-15 -8 3.5817-8 8 0 1.421 0.3816 2.75 1.0312 3.906 0.1079 0.192 0.221 0.381 0.3438 0.563l6.625 11.531 6.625-11.531c0.102-0.151 0.19-0.311 0.281-0.469l0.063-0.094c0.649-1.156 1.031-2.485 1.031-3.906 0-4.4183-3.582-8-8-8zm0 4c2.209 0 4 1.7909 4 4 0 2.209-1.791 4-4 4-2.2091 0-4-1.791-4-4 0-2.2091 1.7909-4 4-4z" fill="#e74c3c" transform="translate(0 1028.4)"/><path d="m12 3c-2.7614 0-5 2.2386-5 5 0 2.761 2.2386 5 5 5 2.761 0 5-2.239 5-5 0-2.7614-2.239-5-5-5zm0 2c1.657 0 3 1.3431 3 3s-1.343 3-3 3-3-1.3431-3-3 1.343-3 3-3z" fill="#c0392b" transform="translate(0 1028.4)"/></g></svg>';
    var svg3='<?xml version="1.0" ?><svg height="48" id="map-marker" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg"><defs><style>.vi-primary {fill: #FF6E6E;fill-rule: evenodd;}.vi-primary, .vi-accent {stroke: #fff;stroke-linecap: round;stroke-width: 0;}.vi-accent {fill: #0C0058;}</style></defs><path class="vi-primary" d="M24,6c7.732,0,14,5.641,14,12.6C38,29.963,24,42,24,42S10,30.064,10,18.6C10,11.641,16.268,6,24,6Z"/><circle class="vi-accent" cx="24" cy="20" r="7"/></svg>';
    var svg4='<?xml version="1.0" ?><svg height="32px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(288 144)"><path d="M-272-144c-6.627,0-12,5.373-12,12s10,20,12,20s12-13.373,12-20S-265.373-144-272-144z M-272-124c-4.412,0-8-3.59-8-8   s3.588-8,8-8s8,3.59,8,8S-267.588-124-272-124z"/></g></svg>';

    var divIcon = L.divIcon({
        html: svg4, //.replace('#','%23'),
        iconAnchor  : [12, 32],
        iconSize    : [25, 30],
        popupAnchor : [0, -28],
        className:"",
    });
//png icon
    var greenIcon = L.icon({
                            //iconUrl: 'images/leaf-green.png',
                            iconUrl: 'images/marker_red.png',
                            //iconUrl: 'images/iconfinder_map-marker_285659_32.png',
                            //shadowUrl: 'images/leaf-shadow.png',
                            iconSize:     [25, 41], // size of the icon
                            //iconSize:     [32, 32], // size of the icon
                            /*shadowSize:   [50, 64], // size of the shadow
                            iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
                            shadowAnchor: [4, 62],  // the same for the shadow
                            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                        */});
    // Create a new vector type with getLatLng and setLatLng methods.
    L.MarkerClusterable = L.Marker.extend({
        _originalInitialize: L.Marker.prototype.initialize,
        
        options:{icon: greenIcon},
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
            disableClusteringAtZoom: 4,
            iconCreateFunction: function (cluster) {
                var childCount = cluster.getChildCount();
                var c = ' marker-cluster-';
                if (childCount < 10) {
                    c += 'small';
                }else if (childCount < 100) {
                    c += 'medium';
                }else {
                    c += 'large';
                }

                return new L.DivIcon({ html: '<div><span>' + childCount + '</span></div>', className: 'marker-cluster' + c, iconSize: new L.Point(40, 40) });
            }
        }).addTo(map);

        
        i=0;
        for(var key1 in features){
            var hists = features[key1];
            for(var key2 in hists){
                var featureJson = hists[key2]["geojson"];
                var mediaHtml = hists[key2]["mediaHtml"];
                var histLink = hists[key2]["histLink"];
                var histDes = hists[key2]["histDes"];
                var histDate = hists[key2]["histDate"];
                var popupC = '<div class="popup"><div class="popupTitle">'+histLink+'</div><div class="popupImage">'+mediaHtml+'</div><div class="popupText">'+histDes+' '+histDate+'</div></div>';
                
                //map.addLayer(featureG);
                var geojsonObject = JSON.parse(featureJson);
                var geojsonFeature = {"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"LineString","coordinates":[[-0.17754077911376956,51.52588012932929],[-0.15299320220947268,51.52545292321852]]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[131.37451171875003,-24.468483631307038]}},{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[121.37695312500001,-30.372875188118016],[118.27880859375001,-29.172627582366303],[116.80664062500001,-26.35249785815401],[118.27880859375001,-23.907265771227095],[121.81640625000001,-22.105998799750566],[124.78271484375001,-23.907265771227095],[126.56250000000001,-26.74561038219901],[124.78271484375001,-29.172627582366303],[121.37695312500001,-30.372875188118016]]]}}]};
                var geojsonLayer = new L.geoJson(geojsonObject,{
        
                    onEachFeature: function(feature, layer) {
                        if (feature.geometry.type === "Polygon") {
                            var clusterable = new L.PolygonClusterable(L.GeoJSON.coordsToLatLngs(feature.geometry.coordinates, 1))
                            .bindPopup(popupC)
                            .addTo(markerClusterLayer);
                        }else if(feature.geometry.type === "LineString") {
                            var clusterable = new L.PolylineClusterable(L.GeoJSON.coordsToLatLngs(feature.geometry.coordinates))
                            //.bindPopup("feature:"+key2+"hist:"+key1)
                            .bindPopup(popupC)
                            .addTo(markerClusterLayer);
                        }else if(feature.geometry.type === "Point") {
                            var clusterable = new L.MarkerClusterable(L.GeoJSON.coordsToLatLng(feature.geometry.coordinates))
                            .bindPopup(popupC)
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
echo $leafLet->widget(['options' => ['id'=>'map','style' => 'min-height: 600px']]);


?>
<br/><br/>
<p>
        <?= Html::a('Create Historical Fact', ['/historicalfact/create'], ['class' => 'btn btn-success']) ?>
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