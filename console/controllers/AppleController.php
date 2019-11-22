<?php
namespace console\controllers;

class AppleController extends \yii\console\Controller
{
    public function actionIndex() {
        echo "cron service runnning";
    }

    public function actionCheckApple(){
        $appleModel = new \common\models\Apple();
        $appleModel->checkAppleRotten();
    }
}