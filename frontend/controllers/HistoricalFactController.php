<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Historicalfact;
use frontend\models\HistoricalfactSearch;
use frontend\models\HistoricalMapLink;
use frontend\models\Map;
use frontend\models\MapAssign;
use frontend\models\User;
use frontend\models\HistoricalAssign;
use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update'],
                'rules' => [
                    /*[
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['SysAdmin'],
                    ],*/
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateHist'],
                        'roleParams' => function() {
                            return ['hist' => Historicalfact::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    
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
     * Lists all my hist models with tabs to other options.
     * @return mixed
     */
    public function actionMyhists()
    {
        $searchModel = new Historicalfact();
        //$params = Yii::$app->request->queryParams;
        //$params['MapSearch']['publicPermission']=1;
        $histAssign = new HistoricalAssign();
        $userId = Yii::$app->user->identity->id;
        $user = User::findOne($userId);
        if(isset(Yii::$app->request->queryParams['HistoricalfactSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getMyhists()
                ->andFilterWhere(Yii::$app->request->queryParams['HistoricalfactSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getMyhists()
            ]);
        return $this->render('myhists', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Lists all assigned hist models with tabs to other options.
     * @return mixed
     */
    public function actionAssignedhists()
    {
        $searchModel = new Historicalfact();
        //$params = Yii::$app->request->queryParams;
        //$params['MapSearch']['publicPermission']=1;
        $histAssign = new HistoricalAssign();
        $userId = Yii::$app->user->identity->id;
        $user = User::findOne($userId);
        if(isset(Yii::$app->request->queryParams['HistoricalfactSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getAssignedhists()
                ->andFilterWhere(Yii::$app->request->queryParams['HistoricalfactSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getAssignedhists()
            ]);
        return $this->render('assignedhists', [
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
     * list for selecting assigned user
     * @param string $q search term
     * @return array list of user id and text
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUserlist($q = null, $id = null){
        //$visitors = ArrayHelper::map(Visitor::find()->orderBy('name')->all(),'id', 'name'); 
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, username AS text')
                ->from('user')
                ->orderBy('username')
                //->where(['like', 'name', $q])
                ->where('username like :q', [':q' => '%'.$q.'%'])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->username];
        }
        return $out;
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
        $userId = Yii::$app->user->identity->id;
        $POST = Yii::$app->request->post();
        if($POST!=null){
            $urls =$POST['HistoricalFact']['urls'];
            if(count($urls)){
                $urlsString = implode(";",$urls);
                $POST['HistoricalFact']['urls'] = $urlsString;
            }else{
                $POST['HistoricalFact']['urls'] = NULL;
            }
        }
        if ($model->load($POST) && $model->save()) {
            $histAssign = new HistoricalAssign();
            $histAssign->histId = $model->id;
            $histAssign->userId = $userId;
            $histAssign->type = 1;
            $histAssign->save();
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
