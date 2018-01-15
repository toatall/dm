<?php


namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Period;
use app\models\Report;
use yii\web\BadRequestHttpException;
use app\assets\AppAsset;



class ReportController extends Controller
{
	
	

	private $_codeIfns;
	
	
	
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
	 *
	 *
	 * @author Трусов Олег Алексеевич <trusov@r86.nalog.ru>
	 * */
	public function actionIndex()
	{
		$model = new Report();			
		return $this->render('index', ['model'=>$model]);
	}
	
	
	/**
	 * Вывод отчета
	 * @throws BadRequestHttpException
	 * @return string
	 */
	public function actionData()
	{
		if (!isset($_POST['Report']))
		{
			throw new BadRequestHttpException();
		}
		
		$model = new Report();
		if (isset($_POST['Report']))
		{
			$model->load(Yii::$app->request->post());			
		}
		
		if ($model->validate())
		{
			$model->printing();
		}
		
		
		if (!Yii::$app->request->isAjax)
		{
			ob_end_clean();			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="DM_'.date('d.m.Y').'.xls"');
			header('Cache-Control: max-age=0');
			if (!count($model->getReport()))
			{
				echo null;
			}
			else 
			{
				echo $this->renderAjax('data',['model'=>$model, 'excel'=>true]);
			}			
			\Yii::$app->end();
		}
		
		
		return $this->renderAjax('data', ['model'=>$model, 'excel'=>false]);
		
	}
	
	
	public function actionViolation($id_reestr, $period, $periodYear, $ifns)
	{
		if (!\Yii::$app->request->isAjax)
			return;
		
		$model = Report::violationModel($id_reestr, $period, $periodYear, $ifns);
		
		return $this->renderAjax('violation', ['model'=>$model]);
	}
	
	
	public function actionT()
	{
		
		print_r(Period::listPeriodByDates(['beginMes'=>'01', 'beginYear'=>2017, 'endMes'=>'08', 'endYear'=>2018]));
	}
	


}

?>