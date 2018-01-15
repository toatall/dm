<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Reestr;
use app\models\Report;



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
                'rules' => [
                	[
						'actions' => ['login', 'error'],
                		'allow' => true,
                		'roles' => ['?','@'],
                	],
                	[
                		'actions' => ['index', 'logout'],
                		'allow' => true,
                		'roles' => ['@'],                		
                	],
                	[                		
                		'allow' => true,
                		'matchCallback' => function($rule, $action) {
                			return isset(\Yii::$app->user->identity->isUfns) && \Yii::$app->user->identity->isUfns;
                		}
                	],            	
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }
    
    
    /**
     * {@inheritDoc}
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
    	if (\Yii::$app->user->isGuest)
    	{
            return $this->redirect(\Yii::$app->user->loginUrl);
    	}    	
    	return parent::beforeAction($action);
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
    public function actionIndex($type=null, $group=null, $department=null)
    {    	
    	if (!isset(\Yii::$app->user->identity->isUfns))
    		return $this->redirect(Yii::$app->user->loginUrl);
    		
    	if (!\Yii::$app->user->identity->isUfns)
    		return $this->redirect(['report/index']);
    	
    	$model = Reestr::publicTable($type,$group,$department);
    	return $this->render('index', [
    		'model'=>$model,
    		'type'=>$type,
    		'group'=>$group,
    		'department'=>$department,
    	]);
    }

    
    /**
     * Аутентификация пользователя
     * 1. Извлечение имени пользователя из переменной $_SERVER['REMOTE_USER'] 
     * (в IIS должна быть настроена windows-аутентификация)
     * 2. Поиск пользователя в БД, если найден, то аутентификация от него
     * 3. Если пользователь не найден в БД, то создание нового пользователя и аутентификация от него
     * 4. Если создание пользователя не удалось, то аутентификация не пройдена 
     * и происходит переадресация на страницу с сообщением об этом (может направление сообщения админу)
     *
     * @return string
     * @author oleg
     * @version 27.03.2017
     */
    public function actionLogin()
    {    	
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = User::findByUsername(User::remoteLogin());
		if ($model!==null)
		{        
			Yii::$app->user->login($model);
			Yii::$app->session->set('dm88_infoAD', $model->rolesAD);
			return $this->goBack();
		}
		
		$this->render('login_error'); 
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
