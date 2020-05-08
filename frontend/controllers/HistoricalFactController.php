<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Historicalfact;
use frontend\models\HistoricalfactSearch;
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
            $urlsString = implode(";",$urls);
            $POST['HistoricalFact']['urls'] = $urlsString;
            //print_r($POST);
        }
        if ($model->load($POST) && $model->save()) {
            $model->urls = explode(";",$model->urls);
            return $this->redirect(['view', 'id' => $model->id]);
        }
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
