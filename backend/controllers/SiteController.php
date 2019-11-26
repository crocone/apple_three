<?php
namespace backend\controllers;

use common\models\Apple;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\ForbiddenHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','generate-apples', 'fall-to-ground','eat'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['generate-apples', 'fall-to-ground','eat'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[
            'treeApples' => Apple::findAll(['status' => Apple::STATUS_HANGING]),
            'groundApples' => Apple::findAll(['status' => [Apple::STATUS_FALL, Apple::STATUS_ROTTEN]])
        ]);
    }

    public function actionGenerateApples(){
        $apple = new Apple();
        if(!$apple->generateApples(Yii::$app->request->get('count', false))){
            return ['result' => 'error'];
        };
        return ['result' => 'success'];
    }


    public function actionFallToGround($id){
        $apple = Apple::findOne($id);
        if (!$apple->fallToGround()) {
            return ['result' => 'error'];
        }
        return ['result' => 'success'];
    }

    public function actionEat($id, $percent){
        $apple = Apple::findOne($id);

        return $apple->eat($percent);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
