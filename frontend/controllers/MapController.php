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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update','create','import'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateMap'],
                        'roleParams' => function() {
                            return ['map' => Map::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    
                ],
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

        return $this->render('index', [
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
        if(isset(Yii::$app->request->queryParams['MapSearch']))
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
        $userId = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
