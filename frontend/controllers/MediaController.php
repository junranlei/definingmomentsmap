<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Media;
use frontend\models\MediaSearch;
use frontend\models\OtherMediaSearch;
use frontend\models\HistoricalFact;
use frontend\models\HistoricalMediaLink;
use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

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
                    'disable' => ['POST'],
                    'enable' => ['POST'],
                    'unlink' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['update'],
                'rules' => [
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
                    /*
                    [
                        'allow' => true,
                        'actions' => ['histlistkupdate'],
                        'roles' => ['updateMedia'],
                        'roleParams' => function() {
                            return ['media' => Media::findOne(['id' => $id])];
                        },
                    ],*/
                    [
                        'allow' => true,
                        'actions' => ['linkother','unlink'],
                        'roles' => ['updateHist'],
                        'roleParams' => function() {
                            return ['hist' => HistoricalFact::findOne(['id' => Yii::$app->request->get('histId')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['histlistupdate'],
                        'roles' => ['updateMedia'],
                        'roleParams' => function() {
                            return ['media' => Media::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['disableMedia'],
                        'roleParams' => function() {
                            return ['media' => Media::findOne(['id' => Yii::$app->request->get('id')])];
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
                        $message='You are not the owner of the media or the media has been linked to more than one historical facts.';
                    else if($action->id=="histlistupdate")
                        $message='You are not the owner of the media and the media is not open for everyone to edit.';
                    else if(in_array($action->id,['linkother','unlink']))
                        $message='You are not the owner or assigned user of the historical fact.';
                    throw new ForbiddenHttpException($message);
         
                }
            ]
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
     * Lists all media models from histId.
     * @return mixed
     */
    public function actionHistlist()
    {
        $searchModel = new MediaSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);
        
        
        if(isset(Yii::$app->request->queryParams['MediaSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia()->andFilterWhere(Yii::$app->request->queryParams['MediaSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia(),
            ]);   
        //$dataProvider->pagination->pageSize=20;


        $model = new Media();
        if(Yii::$app->user->identity!=null&&Yii::$app->user->identity->id!=null)
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
            //save isUrl
            $headers = @get_headers($model->nameOrUrl); 
            $isUrl = False;        
            // Use condition to check the existence of URL 
            if($headers && strpos( $headers[0], '200')) { 
                $isUrl=True; 
                $model->isUrl = 1;
                $model->save();
            } 
            //if there is no main media in historical fact yet, make the newest created media one
            if($historicalFact->mainMediaId==null){
                $model->isMainMedia=1;
            }
            //save mainmediaid is ismainmedia is 1
            if($model->isMainMedia==1){
                $historicalFact->mainMediaId = $model->id;
                $historicalFact->save();
            }
            return $this->redirect(['histlistupdate', 'id' => $model->id, 'histId'=>$histId]);
        }

        $dataProvider->pagination->pageSize=10;

        return $this->render('histlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'histId'=>$histId,
        ]);
    }

    /**
     * Lists all disabled Media models.
     * @return mixed
     */
    public function actionDisabledlist()
    {
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);
        $histId = Yii::$app->request->queryParams["histId"];

        $dataProvider->pagination->pageSize=10;

        return $this->render('disabledlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'histId'=>$histId,
        ]);
    }


     /**
     * Lists all media models from histId with create .
     * @return mixed
     */
    public function actionHistlistcreate()
    {
        $searchModel = new MediaSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);
        
        
        if(isset(Yii::$app->request->queryParams['MediaSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia()->andFilterWhere(Yii::$app->request->queryParams['MediaSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia(),
            ]);   
        //$dataProvider->pagination->pageSize=20;


        $model = new Media();
        if(Yii::$app->user->identity!=null&&Yii::$app->user->identity->id!=null)
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
            //save isUrl
            $headers = @get_headers($model->nameOrUrl); 
            $isUrl = False;        
            // Use condition to check the existence of URL 
            if($headers && strpos( $headers[0], '200')) { 
                $isUrl=True; 
                $model->isUrl = 1;
                $model->save();
            } 
            //if there is no main media in historical fact yet, make the newest created media one
            if($historicalFact->mainMediaId==null){
                $model->isMainMedia=1;
            }
            //save mainmediaid is ismainmedia is 1
            if($model->isMainMedia==1){
                $historicalFact->mainMediaId = $model->id;
                $historicalFact->save();
            }
            return $this->redirect(['histlistupdate', 'id' => $model->id, 'histId'=>$histId]);
        }
        $dataProvider->pagination->pageSize=10;

        return $this->render('histlistcreate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'histId'=>$histId,
        ]);
    }

     /**
     * Lists all unlinked media models from histId, link selected.
     * @return mixed
     */
    public function actionLinkother()
    {
        
        $histId = Yii::$app->request->queryParams["histId"];
        $searchModelLink = new MediaSearch();
        $searchModelLink->search(Yii::$app->request->queryParams);
        $historicalFact = HistoricalFact::findOne($histId);
        
        if(isset(Yii::$app->request->queryParams['MediaSearch']))
            $dataProviderLink = new ActiveDataProvider([
                'query' => Media::find()                  
                    ->where([
                        'not in',
                        'id',
                        $historicalFact->getMedia()->addSelect('id')])
                    ->andFilterWhere(['right2Link' => 1])
                    ->andFilterWhere(['status' => 1])
                    ->andFilterWhere(Yii::$app->request->queryParams['MediaSearch']),
            ]);
        else
            $dataProviderLink = new ActiveDataProvider([
                'query' => Media::find()
                    ->where([
                        'not in',
                        'id',
                        $historicalFact->getMedia()->addSelect('id')])
                    ->andFilterWhere(['right2Link' => 1])
                    ->andFilterWhere(['status' => 1])
            ]);

        $dataProviderLink->pagination->pageSize=10;

        $selection = (array)Yii::$app->request->post('selection'); 
        foreach ($selection as $item) {
            //item is media id
            $histMediaLink = new HistoricalMediaLink();
            $histMediaLink->histId = $histId;
            $histMediaLink->mediaId = $item;
            $histMediaLink->save();
        }
        
        return $this->render('linkother', [
            'searchModelLink' => $searchModelLink,
            'dataProviderLink' => $dataProviderLink,
            'histId'=>$histId,
        ]);
    }


         /**
     * Lists all media models from histId with update .
     * @return mixed
     */
    public function actionHistlistupdate($id)
    {
        $searchModel = new MediaSearch();
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);

        if(isset(Yii::$app->request->queryParams['MediaSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia()->andFilterWhere(Yii::$app->request->queryParams['MediaSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia(),
            ]);
       
        //echo serialize(Yii::$app->request->post()); return;

        $model = $this->findModel($id);
        //check if this is mainmedia
        if($historicalFact->mainMediaId==$id){
            $model->isMainMedia=1;
        }
        if ($model->load(Yii::$app->request->post())&& $model->save()) {
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
             //save isUrl
             $headers = @get_headers($model->nameOrUrl); 
             $isUrl = False;        
             // Use condition to check the existence of URL 
             if($headers && strpos( $headers[0], '200')) { 
                 $isUrl=True; 
                 $model->isUrl = 1;
                 $model->save();
             } 
            if($historicalFact->mainMediaId==$id){
                $model->isMainMedia=1;
            }
            //save mainmediaid is ismainmedia is 1
            if($model->isMainMedia){
                $historicalFact->mainMediaId = $model->id;
                $historicalFact->save();
            }

            return $this->render('histlistupdate', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$model,
                'histId'=>$histId,
            ]);
        }
        $dataProvider->pagination->pageSize=10;

        return $this->render('histlistupdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'histId'=>$histId,
        ]);
    }

             /**
     * Lists all media models from histId with view .
     * @return mixed
     */
    public function actionHistlistview($id)
    {
        $searchModel = new MediaSearch();
        $histId = Yii::$app->request->queryParams["histId"];
        $historicalFact = HistoricalFact::findOne($histId);

        if(isset(Yii::$app->request->queryParams['MediaSearch']))
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia()->andFilterWhere(Yii::$app->request->queryParams['MediaSearch']),
            ]);
        else
            $dataProvider = new ActiveDataProvider([
                'query' => $historicalFact->getMedia(),
            ]);
       
        //echo serialize(Yii::$app->request->post()); return;

        $model = $this->findModel($id);
        //check if this is mainmedia
        if($historicalFact->mainMediaId==$id){
            $model->isMainMedia=1;
        }
        $dataProvider->pagination->pageSize=10;
        return $this->render('histlistview', [
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
     * unlink an Media model from hist.
     * If unlink is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUnlink($id)
    {
        $model = $this->findModel($id);
        $histId = Yii::$app->request->queryParams["histId"];
        //delete associated historicalfact media link
        $histMediaLink = HistoricalMediaLink::findOne(['mediaId'=>$id,'histId'=>$histId]);

        if($histMediaLink!=null){
            $histMediaLink->delete();
        }


        //$model->delete();
        

        return $this->redirect(['histlist', 'histId' => $histId]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        //delete associated historicalfact media links
        /*$histMediaLinks = $model->historicalMediaLinks;
        foreach($histMediaLinks as $histLink){
            if($histLink!=null){
                $histLink->delete();
            }
        }*/
        $model->status=0;
        $model->permission2upload=1;
        $model->save();
        
        $histId = Yii::$app->request->queryParams["histId"];

        return $this->redirect(['histlist', 'histId' => $histId]);
    }

    /**
     * Enable an disabled Media model.
     * If enable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEnable($id)
    {
        $model = $this->findModel($id);
    
        $model->status=1;
        $model->permission2upload=1;
        $model->save();
        
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
            if($model->status!=1&&!\Yii::$app->user->can("SysAdmin")){
                $message="This item has been deleted, please contact us if you would like to recover it.";        
                throw new ForbiddenHttpException($message);
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
