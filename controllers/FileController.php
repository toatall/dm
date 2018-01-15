<?php

namespace app\controllers;

use app\models\Reestr;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use app\models\Data;
use app\models\File;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;


/**
 * ReestrController implements the CRUD actions for Reestr model.
 */
class FileController extends Controller
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
        				'allow' => true,        				
        				'matchCallback' => function($rule, $action) {
        					return User::inRole('admin');
        				}
        				
        			],
        			[
        				'allow' => true,
        				'actions' => ['create', 'index', 'delete'],
        				'roles' => ['@'],
        			],
        		],
        	],        	        
        ];
    }

    
    /**
     * Провайдер для списка файлов
     * @param integer $id_data
     * @return \app\controllers\SqlDataProvider
     */
    private function search($id_data)
    {
    	$query = File::find()->where('id_data=:id_data', [':id_data'=>$id_data]);
    	
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'sort' => false,
    	]);
    	    	
    	return $dataProvider;
    }
    
    
    
	/**
     * Lists all Reestr models.
     * @return mixed
     */
    public function actionIndex($id_data)
    {
    	$modelData = $this->findModelData($id_data);    	
        $dataProvider = $this->search($id_data);
		
        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
        	'modelData' => $modelData,
        ]);
    }

  
    /**
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate($id_data)
    {
        $model = new File();
        $model->id_data = $id_data;
        $modelData = $this->findModelData($id_data);
        
        if (isset($_POST['File']))
        {
        	$model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');        	
        	if ($model->upload())
        	{
        		return $this->actionIndex($id_data);        		
        	}        	
        }
                
        return $this->renderAjax('create', [
        	'model' => $model,
        ]);
        
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
        
        $this->findModelData($model->id_data); // for check access
        
        // удаление файла
        if ($model->deleteFile())
        {
        	$id_data = $model->id_data;
        	$model->delete();
        	return $this->actionIndex($id_data);
        }
        else 
        {
			return '<div class="alert alert-danger">Произошла ошибка при удалении файла "' . $model->filename_original . '"</div>';        	
        }
    }
    
    
    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return \app\models\File
     */
    protected function findModel($id)
    {
    	$model = File::findOne($id);
    	
    	if ($model !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    

    /**
     * Finds the Data model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reestr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelData($id)
    {
    	$model = Data::findOne($id);
    	
    	if ($model===null)
    		throw new NotFoundHttpException('The requested page does not exist.');
    	
        if (Reestr::accessToReestrId($model->id_reestr)) {
            return $model;
        } else {
            throw new ForbiddenHttpException();
        }
    }
}
