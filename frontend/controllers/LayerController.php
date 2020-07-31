<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Layer;
use frontend\models\LayerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Map;

/**
 * LayerController implements the CRUD actions for Layer model.
 */
class LayerController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['maplist','maplistview'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['maplistcreate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['maplistupdate'],
                        'roles' => ['updateMap'],
                        'roleParams' => function() {
                            $layer=$this->findModel(Yii::$app->request->get('id'));
                            $mapId=$layer->map->id;
                            return ['map' => Map::findOne(['id' => $mapId])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['disableMap'],
                        'roleParams' => function() {
                            $layer=$this->findModel(Yii::$app->request->get('id'));
                            $mapId=$layer->map->id;
                            return ['map' => Map::findOne(['id' => $mapId])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disabledlist','enable'],
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
                    else if(in_array($action->id,['maplistupdate']))
                        $message='You are not the owner or assigned user of the map, and the map is not open for everyone to edit.';
                    throw new ForbiddenHttpException($message);
         
                }
                
            ],
        ];
    }

    /**
     * Lists all Layer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LayerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Layer models for one map via mapId.
     * @return mixed
     */
    public function actionMaplist()
    {
        $searchModel = new LayerSearch();
        $mapId = Yii::$app->request->queryParams["mapId"];
        $searchModel->mapId=$mapId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Layer();
        $model->mapId = $mapId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['maplistupdate', 'id' => $model->id, 'mapId'=>$model->mapId]);
        }

        return $this->render('maplist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mapId'=>$mapId
        ]);
    }

    /**
     * Lists all disabled Layer models for one map via mapId.
     * @return mixed
     */
    public function actionDisabledlist()
    {
        $searchModel = new LayerSearch();
        $mapId = Yii::$app->request->queryParams["mapId"];
        $searchModel->mapId=$mapId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);

        return $this->render('disabledlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mapId'=>$mapId
        ]);
    }

    /**
     * Lists all Layer models for one map via mapId with create.
     * @return mixed
     */
    public function actionMaplistcreate()
    {
        $searchModel = new LayerSearch();
        $mapId = Yii::$app->request->queryParams["mapId"];
        $searchModel->mapId=$mapId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Layer();
        $model->mapId = $mapId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['maplistupdate', 'id' => $model->id, 'mapId'=>$model->mapId]);
        }

        return $this->render('maplistcreate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mapId'=>$mapId
        ]);
    }

    /**
     * Lists all Layer models for one map via mapId with update one layer.
     * @return mixed
     */
    public function actionMaplistupdate($id)
    {
        $searchModel = new LayerSearch();
        $mapId = Yii::$app->request->queryParams["mapId"];
        $searchModel->mapId= $mapId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('maplistupdate', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
                'mapId'=>$mapId
            ]);
        }

        return $this->render('maplistupdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'mapId'=>$mapId
        ]);
    }
    /**
     * Lists all Layer models for one map via mapId with view one layer.
     * @return mixed
     */
    public function actionMaplistview($id)
    {
        $searchModel = new LayerSearch();
        $mapId = Yii::$app->request->queryParams["mapId"];
        $searchModel->mapId= $mapId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);

        return $this->render('maplistview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'mapId'=>$mapId
        ]);
    }

    /**
     * Displays a single Layer model.
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
     * Creates a new Layer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Layer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Layer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Layer model.
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
        $mapId = Yii::$app->request->queryParams["mapId"];

        return $this->redirect(['maplist', 'mapId' => $mapId]);
    }

    /**
     * Enables an disabled Layer model.
     * If enable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEnable($id)
    {
        $model=$this->findModel($id);
        $model->status=1;
        $model->save();
        $mapId = Yii::$app->request->queryParams["mapId"];

        return $this->redirect(['maplist', 'mapId' => $mapId]);
    }

    /**
     * Finds the Layer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Layer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Layer::findOne($id)) !== null) {
            if($model->status!=1&&!\Yii::$app->user->can("SysAdmin")){
                $message="This item has been deleted, please contact us if you would like to recover it.";        
                throw new ForbiddenHttpException($message);
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
