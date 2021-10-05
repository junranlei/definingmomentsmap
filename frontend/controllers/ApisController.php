<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Apis;
use frontend\models\ApisSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\HistoricalFact;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * ApisController implements the CRUD actions for Apis model.
 */
class ApisController extends Controller
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
                'rules' => [
                    
                    [
                        'actions' => ['searchapi'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','update','view','create','delete'],
                        'roles' => ['SysAdmin'],
                    ],
                    
                    
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest){
                        Yii::$app->user->loginRequired();return;
                    }
                    $message="You don't have the permisison to perform this action.";
                    
                    throw new ForbiddenHttpException($message);
         
                }
                
            ],
        ];
    }

    /**
     * Lists all Apis models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Apis models.
     * @return mixed
     */
    public function actionSearchapi()
    {
        $searchurl="";
        $querystring = Yii::$app->request->post('querystring')!=null?Yii::$app->request->post('querystring'):"";
        $selectapi = Yii::$app->request->post('selectapi')!=null?Yii::$app->request->post('selectapi'):"";
        $selectfield = Yii::$app->request->post('selectapi')!=null?Yii::$app->request->post('selectfield'):"";
        $view = Yii::$app->request->post('view')!='_jsonview'?Yii::$app->request->post('view'):"";
        if($selectapi!=null&&($selectapi)!=""){
            $api=$this->findModel($selectapi);
            
            if($querystring!=null&&trim($querystring)!=""){
                $searchurl=str_replace("{querystring}", $querystring, $api->url);
            }
        }
        $model = new Apis();
        if($searchurl!=""){
            $data = file_get_contents($searchurl);  
            $model->jsonField=$data;
            //$json_array  = json_decode($data, true);
            //$elementCount  = count($json_array); 
            //check return length for results
            $jsonlen = strlen($data);          
        }

        return $this->renderAjax($view, [
            'apiModel' => $model,
            'selectapi'=>$selectapi,
            'selectfield'=>$selectfield,
            'jsonlen'=>$jsonlen
        ]);
    }

    /**
     * Displays a single Apis model.
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
     * Creates a new Apis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Apis();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Apis model.
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
     * Deletes an existing Apis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apis::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
