<?php


namespace frontend\controllers;
use Yii;

use dosamigos\exportable\services\DownloadService;
use yii\web\Controller;


class ExportController extends Controller {

    // ...

    public function getActions() {
        return [
            'export' => [
                'class' => '\dosamigos\exportable\actions\ExportableAction',
                'filename' => 'users', // without file extension!
                'contentValue' => function($type) { // could be any callable

                       if($type === 'csv') {
                            $contents = "test";

                            return $contents; 
                       }
                       // ... not returning, we redirecting to use behavior for other types :) ...
                       Yii::$app->getResponse()->redirect('where-grid/view-is');
                }
            ]
        ];
    }

    // ...
}