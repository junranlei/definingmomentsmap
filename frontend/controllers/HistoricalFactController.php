<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Historicalfact;
use frontend\models\HistoricalfactSearch;
use frontend\models\HistoricalMapLink;
use frontend\models\Map;
use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HistoricalfactController implements the CRUD actions for Historicalfact model.
 */
class HistoricalfactController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Historicalfact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HistoricalfactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all Historicalfact models for one map via mapId.
     * @return mixed
     */
    public function actionMaplist()
    {
        $searchModel = new HistoricalfactSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $mapId = Yii::$app->request->queryParams["mapId"];
        $map = Map::findOne($mapId);
              
        if(isset(Yii::$app->request->queryParams['HistoricalfactSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $map->getHists()->andFilterWhere(Yii::$app->request->queryParams['HistoricalfactSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $map->getHists(),
            ]);   
        //$dataProvider->pagination->pageSize=20;
        $model = new Historicalfact();
        //url multiple input box combine with ; to string 
        $POST = Yii::$app->request->post();
        if($POST!=null){
            //print_r($POST);
            $urls =$POST['HistoricalFact']['urls'];
            if(count($urls)){
                $urlsString = implode(";",$urls);
                $POST['HistoricalFact']['urls'] = $urlsString;
            }else{
                $POST['HistoricalFact']['urls'] = NULL;
            }
            //print_r($POST);
        }
        if ($model->load($POST) && $model->save()) {
            $histMapLink = new HistoricalMapLink();
            $histMapLink->histId = $model->id;
            $histMapLink->mapId = $mapId;
            $histMapLink->save();
            return $this->redirect(['maplistupdate', 'id' => $model->id, 'mapId'=>$mapId]);
        }
        //url strings transfer to array by ; for multiple input box 
        if(trim($model->urls!="")&&$model->urls!=NULL)
            $model->urls = explode(";",$model->urls);
        return $this->render('maplist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mapId'=>$mapId,
            'model'=>$model,
        ]);
    }

         /**
     * Lists all unlinked hist models from mapId, link selected.
     * @return mixed
     */
    public function actionLinkother()
    {
        
        $mapId = Yii::$app->request->queryParams["mapId"];
        $searchModelLink = new HistoricalfactSearch();
        $searchModelLink->search(Yii::$app->request->queryParams);
        $map = Map::findOne($mapId);
        
        if(isset(Yii::$app->request->queryParams['HistoricalfactSearch']))
            $dataProviderLink = new ActiveDataProvider([
                'query' => Historicalfact::find()                  
                    ->where([
                        'not in',
                        'id',
                        $map->getHists()->addSelect('id')])
                    //->andFilterWhere(['right2Link' => 1])
                    ->andFilterWhere(Yii::$app->request->queryParams['HistoricalfactSearch']),
            ]);
        else
            $dataProviderLink = new ActiveDataProvider([
                'query' => Historicalfact::find()
                    ->where([
                        'not in',
                        'id',
                        $map->getHists()->addSelect('id')])
                    //->andFilterWhere(['right2Link' => 1])
            ]);

            
        $selection = (array)Yii::$app->request->post('selection'); 
        foreach ($selection as $item) {
            //item is media id
            $histMapLink = new HistoricalMapLink();
            $histMapLink->mapId = $mapId;
            $histMapLink->histId = $item;
            $histMapLink->save();
        }
        
        return $this->render('linkother', [
            'searchModelLink' => $searchModelLink,
            'dataProviderLink' => $dataProviderLink,
            'mapId'=>$mapId,
        ]);
    }

    /**
     * Lists all Layer models for one map via mapId with update one layer.
     * @return mixed
     */
    public function actionMaplistupdate($id)
    {
        $searchModel = new HistoricalfactSearch();
        $mapId = Yii::$app->request->queryParams["mapId"];
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);   
        $map = Map::findOne($mapId);

        if(isset(Yii::$app->request->queryParams['HistoricalfactSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $map->getHists()->andFilterWhere(Yii::$app->request->queryParams['HistoricalfactSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $map->getHists(),
            ]);   
        

        $model = $this->findModel($id);
        //url multiple input box combine with ; to string 
        $POST = Yii::$app->request->post();
        if($POST!=null){
            //print_r($POST);
            $urls =$POST['HistoricalFact']['urls'];
            if(count($urls)){
                $urlsString = implode(";",$urls);
                $POST['HistoricalFact']['urls'] = $urlsString;
            }else{
                $POST['HistoricalFact']['urls'] = NULL;
            }
            //print_r($POST);
        }
        if ($model->load($POST) && $model->save()) {
            return $this->render('maplistupdate', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
                'mapId'=>$mapId,
            ]);
        }
        //url strings transfer to array by ; for multiple input box 
        if(trim($model->urls!="")&&$model->urls!=NULL)
            $model->urls = explode(";",$model->urls);
        return $this->render('maplistupdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'mapId'=>$mapId,
        ]);
    }

    /**
     * Displays a single Historicalfact model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Historicalfact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Historicalfact();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Historicalfact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //print serialize(Yii::$app->request->post());
        $POST = Yii::$app->request->post();
        if(Yii::$app->request->post()!=null){
            //print_r($POST);
            $urls =$POST['HistoricalFact']['urls'];
            if(count($urls)){
                $urlsString = implode(";",$urls);
                $POST['HistoricalFact']['urls'] = $urlsString;
            }else{
                $POST['HistoricalFact']['urls'] = NULL;
            }
            //print_r($POST);
        }
        if ($model->load($POST) && $model->save()) {
            //$model->urls = explode(";",$model->urls);
            return $this->redirect(['update', 'id' => $model->id]);
        }
        if(trim($model->urls!="")&&$model->urls!=NULL)
            $model->urls = explode(";",$model->urls);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Historicalfact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Historicalfact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Historicalfact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Historicalfact::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
