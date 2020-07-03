<?php

namespace frontend\controllers;
use yii\data\ActiveDataProvider;

use Yii;
use frontend\models\Map;
use frontend\models\MapSearch;
use frontend\models\HistoricalFact;
use frontend\models\HistoricalMapLink;
use frontend\models\MapAssign;
use frontend\models\User;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MapController implements the CRUD actions for Map model.
 */
class MapController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['update','create','import'],
                'rules' => [
                    [
                        'actions' => ['view','index','histlinkedmaps'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['mymaps','assignedmaps','create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','userlist'],
                        'roles' => ['updateMap'],
                        'roleParams' => function() {
                            return ['map' => Map::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['disableMap'],
                        'roleParams' => function() {
                            return ['map' => Map::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disabledmaps','enable'],
                        'roles' => ['SysAdmin'],
                        
                    ]   
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest){
                        Yii::$app->user->loginRequired();return;
                    }
                    $message="You don't have the permisison to perform this action.";
                    if($action->id=="disable")
                        $message='You are not the owner of the map';
                    else if(in_array($action->id,['update','userlist']))
                        $message='You are not the owner or assigned user of the map, and the map is not open for everyone to edit.';
                    throw new ForbiddenHttpException($message);
         
                }
            ],
        ];
    }

    /**
     * Lists all public Map models with tabs to other options.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MapSearch();
        $params = Yii::$app->request->queryParams;
        //$params['MapSearch']['publicPermission']=1;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all disabled Map models with tabs to other options.
     * @return mixed
     */
    public function actionDisabledmaps()
    {
        $searchModel = new MapSearch();
        $params = Yii::$app->request->queryParams;
        //$params['MapSearch']['publicPermission']=1;
        $dataProvider = $searchModel->search($params, $status=0);

        return $this->render('disabledmaps', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all my Map models with tabs to other options.
     * @return mixed
     */
    public function actionMymaps()
    {
        $searchModel = new MapSearch();
        //$params = Yii::$app->request->queryParams;
        //$params['MapSearch']['publicPermission']=1;
        $mapAssign = new MapAssign();

        $userId = Yii::$app->user->identity->id;
        $user = User::findOne($userId);

        if(isset(Yii::$app->request->queryParams['MapSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getMymaps()
                ->andFilterWhere(Yii::$app->request->queryParams['mapSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getMymaps()
            ]);
        return $this->render('mymaps', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Lists all assigned Map models with tabs to other options.
     * @return mixed
     */
    public function actionAssignedmaps()
    {
        $searchModel = new MapSearch();
        //$params = Yii::$app->request->queryParams;
        //$params['MapSearch']['publicPermission']=1;
        $mapAssign = new MapAssign();
        $userId = Yii::$app->user->identity->id;
        $user = User::findOne($userId);
        
        if(isset(Yii::$app->request->queryParams['MapSearch'])&&$user!=null)
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getAssignedmaps()
                ->andFilterWhere(Yii::$app->request->queryParams['mapSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $user->getAssignedmaps()
            ]);
        return $this->render('assignedmaps', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Map models.
     * @return mixed
     */
    public function actionHistlinkedmaps()
    {
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);
        $searchModel = new MapSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $historicalFact->getMaps(),
        ]);   

        return $this->render('histlinkedmaps', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'histId'=>$histId
        ]);
    }

    /**
     * Displays a single Map model.
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
     * Creates a new Map model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Map();
        if(Yii::$app->user->identity!=null&&Yii::$app->user->identity->id!=null)
            $userId = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()&&$userId!=null) {
            $mapAssign = new MapAssign();
            $mapAssign->mapId = $model->id;
            $mapAssign->userId = $userId;
            $mapAssign->type = 1;
            $mapAssign->save();
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Map model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //assigned user block
            $post = Yii::$app->request->post();
            $assignedUsers = $post['Map']['assignedUsers'];
            //delete old vds
            $existingAus = MapAssign::find()
                                ->where(['mapId' => $id])
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
                    $newVds = new MapAssign();
                    $newVds->mapId = $id;
                    $newVds->userId = $existingDs->id;
                    $newVds->type=2;
                    $newVds->save();
                }
                
            }

            //assigned user block
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Map model.
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
     * Enables an disabled Map model.
     * If enable is successful, the browser will be redirected to the 'index' page.
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
     * Finds the Map model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Map the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Map::findOne($id)) !== null) {
            if($model->status!=1&&!\Yii::$app->user->can("SysAdmin")){
                $message="This item has been deleted, please contact us if you would like to recover it.";        
                throw new ForbiddenHttpException($message);
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
