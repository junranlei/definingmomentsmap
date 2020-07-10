<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Flag;
use frontend\models\FlagNote;
use frontend\models\FlagSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FlagController implements the CRUD actions for Flag model.
 */
class FlagController extends Controller
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
                    'Flagmap' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['flagmap'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['SysAdmin'],
                        
                    ] 
                    
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
     * Lists all Flag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FlagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFlagmap()
    {
        $model = new FlagNote();

        $mId = Yii::$app->request->queryParams["id"];
        $m = Yii::$app->request->queryParams["m"];
        $model->m = $m;
        $model->mId = $mId;
        if ($model->load(Yii::$app->request->post()) ) {
            $flagModel = Flag::find()->where(['model' => $m, 'modelId'=>$mId])->one();
            if($flagModel!=null){
                $flagModel->times = $flagModel->times+1;

            }else{
                $flagModel=new Flag();
                $flagModel->model = $model->m;
                $flagModel->modelId = $model->mId;
                $flagModel->times = 1;
            }
            if($flagModel->save()){
                if($m=="map"){
                    $map=$flagModel->modelMap;
                    if($map!=null){
                        $map->flag=1;
                        $map->save();
                    }
                }else if($m=="historicalfact"){
                    $hist=$flagModel->modelHist;
                    if($hist!=null){
                        $hist->flag=1;
                        $hist->save();
                    }
                }else if($m=="media"){
                    $media=$flagModel->modelMedia;
                    if($media!=null){
                        $media->flag=1;
                        $media->save();
                    }
                }
                $model->flagId = $flagModel->id;
                $model->userId = \Yii::$app->user->id;
                $model->save();
            }
            return $this->redirect(\Yii::$app->request->referrer);
        }else
        return $this->renderAjax('_note', [
                    'model' => $model,
            ]);

    }

    /**
     * Displays a single Flag model.
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
     * Creates a new Flag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Flag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Flag model.
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
     * Deletes an existing Flag model.
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
     * Finds the Flag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Flag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Flag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
