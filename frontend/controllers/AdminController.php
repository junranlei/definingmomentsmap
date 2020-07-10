<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FlagnoteController implements the CRUD actions for FlagNote model.
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    
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
     * Display admin page.
     * @return mixed
     */
    public function actionIndex()
    {
        
        return $this->render('index');
    }

   
}
