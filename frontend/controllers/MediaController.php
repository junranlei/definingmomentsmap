<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Media;
use frontend\models\MediaSearch;
use frontend\models\HistoricalFact;
use frontend\models\HistoricalMediaLink;
use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
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
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Lists all media models from histId with create .
     * @return mixed
     */
    public function actionHistlist()
    {
        $searchModel = new MediaSearch();
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $historicalFact->getMedia(),
        ]);

        $model = new Media();
        $model->ownerId=Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //save historicalfact media relationship
            $histMediaLink = new HistoricalMediaLink();
            $histMediaLink->histId = $histId;
            $histMediaLink->mediaId = $model->id;
            $histMediaLink->save();
            $id = $model->id;
            //save uploaded file if there is any
            if($model->files = UploadedFile::getInstance($model, 'files')){
                if (!is_dir('uploads/'.$id)) {
                    mkdir('uploads/'.$id, 0755, true);
                }
                $model->files->saveAs('uploads/'.$id.'/' . $model->files->baseName . '.' . $model->files->extension);
                $newfile = $model->files->baseName . '.' . $model->files->extension;
                $model->nameOrUrl = $newfile;              
                $model->save();
                
            }
            return $this->redirect(['histlistupdate', 'id' => $model->id, 'histId'=>$histId]);
        }

        return $this->render('histlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'histId'=>$histId,
        ]);
    }

         /**
     * Lists all media models from histId with create .
     * @return mixed
     */
    public function actionHistlistupdate($id)
    {
        $searchModel = new MediaSearch();
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $historicalFact->getMedia(),
        ]);

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //save uploaded file if there is any
            if($model->files = UploadedFile::getInstance($model, 'files')){
                if (!is_dir('uploads/'.$id)) {
                    mkdir('uploads/'.$id, 0755, true);
                }
                $model->files->saveAs('uploads/'.$id.'/' . $model->files->baseName . '.' . $model->files->extension);
                $newfile = $model->files->baseName . '.' . $model->files->extension;
                $model->nameOrUrl = $newfile;
                $model->save();
                
            }
            
            return $this->render('histlistupdate', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$model,
                'histId'=>$histId,
            ]);
        }

        return $this->render('histlistupdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'histId'=>$histId,
        ]);
    }

    /**
     * Displays a single Media model.
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
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Media();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->files = UploadedFile::getInstance($model, 'files')){
                if (!is_dir('uploads/'.$id)) {
                    mkdir('uploads/'.$id, 0755, true);
                }
                $model->files->saveAs('uploads/'.$id.'/' . $model->files->baseName . '.' . $model->files->extension);
                $newfile = $model->files->baseName . '.' . $model->files->extension;
                $model->nameOrUrl = $newfile;
                $model->save();
                /*if(!strstr($relatedFiles,$newfile)){
                    if(trim($relatedFiles)!="") $relatedFiles=$relatedFiles. ";";
                    $model->relatedFiles = $relatedFiles.$newfile;
                }*/
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //delete associated historicalfact media links
        $histMediaLinks = $model->historicalMediaLinks;
        foreach($histMediaLinks as $histLink){
            if($histLink!=null){
                $histLink->delete();
            }
        }

        $model->delete();
        $histId = Yii::$app->request->queryParams["histId"];

        return $this->redirect(['histlist', 'histId' => $histId]);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
