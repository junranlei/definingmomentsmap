<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Layer;
use frontend\models\LayerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
                    'delete' => ['POST'],
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
                        'actions' => ['maplistupdate','delete'],
                        'roles' => ['updateMap'],
                        'roleParams' => function() {
                            $layer=$this->findModel(Yii::$app->request->get('id'));
                            $mapId=$layer->map->id;
                            return ['map' => Map::findOne(['id' => $mapId])];
                        },
                    ],
                    
                ],
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
