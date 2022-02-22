<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\GithubRepository;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        $request = Yii::$app->request;
        $needUpdateRepositories = $request->get('update'); 
        // если нужно обновить список репозиториев вручную, то вызовем консольную команду обновления
        if ($needUpdateRepositories) {
            $consoleController = new \app\commands\GetGitHubUserRepositoriesController(false, Yii::$app);
            $consoleController->runAction('index');
        }
        
        // дата, когда мы последний раз обновляли информацию по репозиториям 
        $dateUpdateUserRepositories = Yii::$app->cache->get('timestampWhenUpdatedUserRepositories');
        if ($dateUpdateUserRepositories) {
            $dateUpdateUserRepositories = date('Y-m-d H:i:s', $dateUpdateUserRepositories);
        }
        
        $githubRepositories = GithubRepository::find()->orderBy('updated DESC')->all();
        return $this->render('index', [
            'githubRepositories'         => $githubRepositories, 
            'dateUpdateUserRepositories' => $dateUpdateUserRepositories]
        );
    }

}
