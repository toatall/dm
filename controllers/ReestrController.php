<?php

namespace app\controllers;

use Yii;
use app\models\Reestr;
use app\models\ReestrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\filters\AccessControl;
use yii\db\Expression;
use app\models\Ifns;
use app\models\Data;
use app\models\Period;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;


/**
 * ReestrController implements the CRUD actions for Reestr model.
 */
class ReestrController extends Controller
{
	
	
	private $errorValidateViolation = [];
	
	
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
        				'allow' => true,        				
        				'matchCallback' => function($rule, $action) {
        					return User::inRole('admin');
        				}
        				
        			],  
        			[
        				'allow' => true,
        				'actions' => ['period', 'violation'],
        				'roles' => ['@'],
        			],
        		],
        	],
        	
        	'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Reestr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReestrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reestr model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reestr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reestr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveRelationDepartment($model->cacheDepartments);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {        	
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reestr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Reestr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->date_delete = new Expression('getdate()');
        $model->save();

        return $this->redirect(['index']);
    }
    
    
    
    private function convertErrorsToText($errors)
    {
    	$errorText = '';
    	foreach ($errors as $error)
    	{
    		$errorText .= implode('<br />', $error) . '<br />';
    	}
    	return $errorText;
    }
    
    
    /**
     * 
     * Генерация моделей для ввода показателей
     * 
     * @param unknown $id
     * @param unknown $periodType
     * @param unknown $periodValue
     * @param unknown $periodYear
     * @throws BadRequestHttpException
     * 
     * @return Data[]
     */
    private function generateDataForm($id, $periodType, $periodYear)
    {
    	$resultModel = [];
    	
    	if (!Period::checkPeridCorrect($periodType, $periodYear))
    	{
    		throw new BadRequestHttpException('Некорректный запрос');
    	}
    	
    	foreach (Ifns::find()->where('disable_no=0')->all() as $ifns)
    	{    		
    		$resultModel[$ifns->code_no] = Data::loadCreateData($id, $ifns->code_no, $periodType, $periodYear);	
    		$resultModel[$ifns->code_no]->code_no = $ifns->code_no;
    		
    		
    		if (isset($_POST['Data']))
    		{
    			$resultModel[$ifns->code_no]->isPost = true;
    			if ($resultModel[$ifns->code_no]->load($_POST['Data'], $ifns->code_no))
    			{
    				$resultModel[$ifns->code_no]->id_reestr = $id;
    				$resultModel[$ifns->code_no]->period = $periodType;    				
    				$resultModel[$ifns->code_no]->period_year = $periodYear;
    				$resultModel[$ifns->code_no]->author_id = (isset(\Yii::$app->user->id) ? \Yii::$app->user->id : 0);    				
    				$resultModel[$ifns->code_no]->validate();
    				
    				if (count($resultModel[$ifns->code_no]->errors))
    				{
    					$this->errorValidateViolation[$ifns->code_no] = $this->convertErrorsToText($resultModel[$ifns->code_no]->errors);
    				}
    				else
    				{
    					$resultModel[$ifns->code_no]->save();
    				}
    			}
    		}
    	}
    	
    	return $resultModel;
    	
    }
        
    
    /**
     * Сохранение показателей
     * @param unknown $id
     * @param unknown $type
     * @param unknown $value
     * @param unknown $year
     * @return string
     */
    public function actionViolation($id, $type, $year)
    {    	
    	$model = $this->findModel($id);
    	
    	$modelData = $this->generateDataForm($id, $type, $year);
    	
    	$errors = [];
    	
    	if (isset($_POST['Data']) && !count($this->errorValidateViolation))
    	{    	
    		return '<div class="alert alert-success">Данные успешно сохранены! <br /><br /><button class="btn btn-primary" data-dismiss="modal">Закрыть</button></div>';
    	}
    	
    	    	
    	return $this->renderAjax('violation', [
    		'model'=>$model, 
    		'modelData'=>$modelData,
    		'url'=>Url::to(['reestr/violation', 'id'=>$id, 'type'=>$type, 'year'=>$year]),
    		'errorsValidate' => $this->errorValidateViolation,
    	]);
    }
    
    
    
    /**
     * Панель для задания периода
     * @param unknown $id
     * @return unknown
     */
    public function actionPeriod($id)
    {
    	$model = $this->findModel($id);
    	return $this->renderPartial('_period', ['model'=>$model]);
    }

    /**
     * Finds the Reestr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reestr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reestr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
