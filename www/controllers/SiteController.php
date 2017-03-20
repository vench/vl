<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
        return $this->render('index');
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
        }
        return $this->render('login', [
            'model' => $model,
        ]);
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

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    
    public function actionActive($token) {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        /* @var $user User */
        $user = User::find()->where('remoteToken =:remoteToken', [
            ':remoteToken'  => $token,
        ])->one();
        
        if(is_null($user)) {
            throw new \yii\web\NotFoundHttpException("User not found");
        }
        
        if($user->is_active) {
            throw new \yii\web\ForbiddenHttpException("User is active");
        }
        
        
        $user->is_active = 1;
        $user->save(false, ['is_active']);
        
        Yii::$app->user->login($user,  3600*24*30);
        Yii::$app->session->setFlash('RefreshInfo', 'Hello  '.$user->name.'!');
        return $this->goHome();
        
    }
    
    /**
     * 
     * @return type
     */
    public function actionRegistered() {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new \app\models\RegisteredForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->createUser();
            Yii::$app->session->setFlash('RefreshInfo', 'Go to your email: '.$model->email.'');
            return $this->goHome();
        }
        
        return $this->render('registered', [
            'model' => $model,
        ]);
    }
}
