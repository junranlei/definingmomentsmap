<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Feature;
use frontend\models\FeatureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\HistoricalFact;


/**
 * FeatureController implements the CRUD actions for Feature model.
 */
class FeatureController extends Controller
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
                //'only' => ['update'],
                'rules' => [
                    /*[
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['SysAdmin'],
                    ],*/
                    [
                        'actions' => ['histlist','histlistview'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['histlistcreate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['histlistupdate'],
                        'roles' => ['updateHist'],
                        'roleParams' => function() {
                            $feature=$this->findModel(Yii::$app->request->get('id'));
                            $histId=$feature->hist->id;
                            return ['hist' => HistoricalFact::findOne(['id' => $histId])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['disableHist'],
                        //other handler: denycallback in each allow condition
                        /*'denyCallback' => function ($rule, $action) {
                            $message='You are not the owner or the historical facts has been linked to more than one maps.';
                            throw new ForbiddenHttpException($message);
                            //throw new NotFoundHttpException("Something unexpected happened");
                            //$message='You are not the owner or the historical facts has been linked to more than one maps.';
                            //$this->redirect(array('site/error'));
                        },*/
                        'roleParams' => function() {
                            $feature=$this->findModel(Yii::$app->request->get('id'));
                            $histId=$feature->hist->id;
                            return ['hist' => HistoricalFact::findOne(['id' => $histId])];
                        }
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
                        $message='You are not the owner or the historical facts has been linked to more than one maps.';
                    else if($action->id=="histlistupdate")
                        $message='You are not the owner or assigned user of the historical facts, and the historical fact is not open for everyone to edit.';
                    //else {Yii::$app->user->loginRequired();return;}
                    throw new ForbiddenHttpException($message);
                    //Add your error handler here, other handlers:
                    //if($rule==)
                    //Yii::$app->session->setFlash('error', $rule.
                    //'You are not the owner or the historical facts has been linked to more than one maps.');
                    //$this->redirect(array('site/error'));
                    //Yii::$app->user->loginRequired();   
                    //$message = 'You are not allowed to perform this action.';
                    //throw new \yii\web\AccessDeniedHttpException(Yii::t('yii', $message));  
                    //throw new \Exception('You are not allowed to access this page');           
                }
            ]
        ];
    }

    /**
     * Lists all Feature models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeatureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Feature models from histId.
     * @return mixed
     */
    public function actionHistlist()
    {
        $searchModel = new FeatureSearch();
        $searchModel->histId=Yii::$app->request->queryParams["histId"];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Feature();
        $model->histId = $searchModel->histId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['histlistupdate', 'id' => $model->id, 'histId'=>$model->histId]);
        }

        return $this->render('histlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Feature models from histId.
     * @return mixed
     */
    public function actionDisabledlist()
    {
        $searchModel = new FeatureSearch();
        $searchModel->histId=Yii::$app->request->queryParams["histId"];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);

        return $this->render('disabledlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Feature models from histId with create.
     * @return mixed
     */
    public function actionHistlistcreate()
    {
        $searchModel = new FeatureSearch();
        $searchModel->histId=Yii::$app->request->queryParams["histId"];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Feature();
        $model->histId = $searchModel->histId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['histlistupdate', 'id' => $model->id, 'histId'=>$model->histId]);
        }

        return $this->render('histlistcreate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Feature models from histId with update one .
     * @return mixed
     */
    public function actionHistlistupdate($id)
    {
        $searchModel = new FeatureSearch();
        $searchModel->histId=Yii::$app->request->queryParams["histId"];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('histlistupdate', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }

        return $this->render('histlistupdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Lists all Feature models from histId with view one .
     * @return mixed
     */
    public function actionHistlistview($id)
    {
        $searchModel = new FeatureSearch();
        $searchModel->histId=Yii::$app->request->queryParams["histId"];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        

        return $this->render('histlistview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Feature model.
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
     * Creates a new Feature model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Feature();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Feature model.
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
     * Deletes an existing Feature model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDisable($id)
    {
        $model=$this->findModel($id);
        $model->status=0;
        $model->save();
        $histId = Yii::$app->request->queryParams["histId"];

        return $this->redirect(['histlist', 'histId' => $histId]);
    }

    /**
     * Enables an disabled Feature model.
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
        $histId = Yii::$app->request->queryParams["histId"];

        return $this->redirect(['histlist', 'histId' => $histId]);
    }

    /**
     * Finds the Feature model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feature the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feature::findOne($id)) !== null) {
            if($model->status!=1&&!\Yii::$app->user->can("SysAdmin")){
                $message="This item has been deleted, please contact us if you would like to recover it.";        
                throw new ForbiddenHttpException($message);
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
