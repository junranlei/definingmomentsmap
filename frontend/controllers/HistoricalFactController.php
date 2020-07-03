<?php

namespace frontend\controllers;

use Yii;
use frontend\models\HistoricalFact;
use frontend\models\HistoricalfactSearch;
use frontend\models\HistoricalMapLink;
use frontend\models\Map;
use frontend\models\MapAssign;
use frontend\models\User;
use frontend\models\HistoricalAssign;
use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * HistoricalFactController implements the CRUD actions for HistoricalFact model.
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
                    'disable' => ['POST'],
                    'enable' => ['POST'],
                    'unlink' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['update'],
                'rules' => [
                    /*[
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['SysAdmin'],
                    ],*/
                    [
                        'actions' => ['view','index','maplist'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['myhists','assignedhists','create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','maplistupdate','userlist','unlink'],
                        'roles' => ['updateHist'],
                        'roleParams' => function() {
                            return ['hist' => HistoricalFact::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['disableHist'],
                        'roleParams' => function() {
                            return ['hist' => HistoricalFact::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['linkother'],
                        'roles' => ['updateMap'],
                        'roleParams' => function() {
                            return ['map' => Map::findOne(['id' => Yii::$app->request->get('mapId')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disabledhists','enable'],
                        'roles' => ['SysAdmin'],
                        
                    ]          
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest){
                        Yii::$app->user->loginRequired();return;
                    }
                    $message="You don't have the permisison to perform this action.";
                    if($action->id=="disable")
                        $message='You are not the owner or the historical fact has been linked to more than one maps.';
                    else if(in_array($action->id,['update','maplistupdate','userlist','unlink']))
                        $message='You are not the owner or assigned user of the historical fact, and the historical fact is not open for everyone to edit.';
                    throw new ForbiddenHttpException($message);
         
                }
            ],
        ];
    }

    /**
     * Lists all HistoricalFact models.
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
     * Lists all disabled Historicalfact models. only sysadmin can see
     * @return mixed
     */
    public function actionDisabledhists()
    {
        $searchModel = new HistoricalfactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $status=0);

        return $this->render('disabledhists', [
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
        $searchModel = new HistoricalfactSearch();
        $searchModel->search(Yii::$app->request->queryParams);
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
            'histMenu'=>True,
        ]);
    }

     /**
     * Lists all assigned hist models with tabs to other options.
     * @return mixed
     */
    public function actionAssignedhists()
    {
        $searchModel = new HistoricalfactSearch();
        $searchModel->search(Yii::$app->request->queryParams);
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
                'query' => $map->getHists()->andFilterWhere(Yii::$app->request->queryParams['HistoricalFactSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $map->getHists(),
            ]);   
        //$dataProvider->pagination->pageSize=20;
        $model = new HistoricalFact();
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
                'query' => HistoricalFact::find()                  
                    ->where([
                        'not in',
                        'id',
                        $map->getHists()->addSelect('id')])
                    //->andFilterWhere(['right2Link' => 1])
                    ->andFilterWhere(Yii::$app->request->queryParams['HistoricalfactSearch']),
            ]);
        else
            $dataProviderLink = new ActiveDataProvider([
                'query' => HistoricalFact::find()
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
     * Lists all hist models for one map via mapId with update one hist.
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
     * Displays a single HistoricalFact model.
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
     * Creates a new HistoricalFact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HistoricalFact();
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
     * Updates an existing HistoricalFact model.
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

            //assigned user block
            $post = Yii::$app->request->post();
            $assignedUsers = $post['HistoricalFact']['assignedUsers'];
            //delete old vds
            $existingAus = HistoricalAssign::find()
                                ->where(['histId' => $id])
                                ->all();
            foreach($existingAus as $au){
                $au->delete();
            }
            if(is_array($assignedUsers))
            foreach($assignedUsers as $ds){
                
                //is number find the user by id
                $existingDs = User::find()
                        ->where(['id' => trim($ds)])
                        ->one(); 
                //link it to user by adding to historicalAssign table
                if($existingDs!=null&&$existingDs->id!=null){
                    $newVds = new HistoricalAssign();
                    $newVds->histId = $id;
                    $newVds->userId = $existingDs->id;
                    $newVds->type=2;
                    $newVds->save();
                }
                
            }

            //assigned user block

            
            return $this->redirect(['update', 'id' => $model->id]);
        }
        if(trim($model->urls!="")&&$model->urls!=NULL)
            $model->urls = explode(";",$model->urls);
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /**
     * unlink an hist model from map.
     * If unlink is successful, the browser will be redirected to the 'maplist' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUnlink($id)
    {
        $model = $this->findModel($id);
        $mapId = Yii::$app->request->queryParams["mapId"];
        //delete associated historicalfact media link
        $histMapLink = HistoricalMapLink::findOne(['histId'=>$id,'mapId'=>$mapId]);

        if($histMapLink!=null){
            $histMapLink->delete();
        }


        //$model->delete();
        

        return $this->redirect(['maplist', 'mapId' => $mapId]);
    }

    /**
     * Deletes an existing HistoricalFact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDisable($id)
    {
        //$this->findModel($id)->delete();
        $model=$this->findModel($id);
        $model->status=0;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Enable an disabled HistoricalFact model.
     * If enabled is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEnable($id)
    {
        //$this->findModel($id)->delete();
        $model=$this->findModel($id);
        $model->status=1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the HistoricalFact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HistoricalFact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HistoricalFact::findOne($id)) !== null) {
            if($model->status!=1&&!\Yii::$app->user->can("SysAdmin")){
                $message="This item has been deleted, please contact us if you would like to recover it.";        
                throw new ForbiddenHttpException($message);
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
