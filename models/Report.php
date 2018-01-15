<?php


namespace app\models;

use app\models\Period;
use app\models\Ifns;
use yii\helpers\ArrayHelper;
use app\models\Reestr;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class Report extends Model
{
	
	// окраска для типичных/типовых вопросов
	const COLOR_TYPICAL_VIOLATION = '#CCFFCC';
	const COLOR_NOT_TYPICAL_VIOLATION = '#FFFFCC';
	
	
	// фильтр входящих данных
	public $periodBeginMonth;
	public $periodBeginYear;
	public $periodEndMonth;
	public $periodEndYear;
	public $ifns = null;
	public $groups = null;
	public $type = null;
	public $departments = null;
	public $periodType = null;
	
	private $periods;
	
	
	/**
	 * Данные отчета
	 * @var array
	 *
	 * => [
	 * 	'title' => [ заголовки ]
	 *  'data' => []
	 *
	 * ]
	 *
	 */
	private $reportData = [];
	
	
	
	
	public function rules()
	{
		return [
			[['periodBeginMonth', 'periodBeginYear', 'periodEndMonth', 'periodEndYear'], 'required'],
			[['departments', 'groups', 'type'], 'safe'],
		];
	}
	
	
	
	public function attributeLabels()
	{
		return [
			'periodBeginMonth' => 'месяц с',
			'periodBeginYear' => 'год с',
			'periodEndMonth' => 'месяц по',
			'periodEndYear' => 'год по',
		];
	}
	
	
	
	/**
	 * {@inheritDoc}
	 * @see \yii\base\Object::init()
	 */
	/*
	public function init()
	{
		$this->departments = $this->getListDepartment();
		$this->groups = $this->getListGroup();
		parent::init();
	}
	*/
	
	
	/**
	 * 
	 * @param unknown $departments
	 * @param unknown $ifns
	 * @param unknown $groups
	 * @param unknown $typeViolation
	 * return [] $this->reportData
	 */
	public function printing()
	{
	
		// определение периодов
		$this->periods = Period::listPeriodByDates([
			'beginMes' => $this->periodBeginMonth, 
			'beginYear' => $this->periodBeginYear,
			'endMes' => $this->periodEndMonth,
			'endYear' => $this->periodEndYear,
		]);
		
		if (!count($this->periods))
			return $this->reportData;
		
		
		// определение перечня инспекций
		// если пользователь - сотрудник ИФНС, то он имеет доступ, только с совей организации
		if (\Yii::$app->user->identity->isUfns)
		{
			if ($this->ifns == null || count($this->ifns)==0)
			{
				$this->ifns = ArrayHelper::map(Ifns::find()->all(), 'code_no', 'code_no');
			}
			
			if (!isset($this->ifns['8600']))
				$this->ifns['8600'] = '8600';
			
		}
		else
		{
			$this->ifns[\Yii::$app->user->identity->org_code] = \Yii::$app->user->identity->org_code;
		}
		
		ksort($this->ifns);
		
		
		$this->reportData['periods'] = $this->periods;
		$this->reportData['ifns'] = $this->ifns;
		$this->reportData['data'] = [];
		
		
		$modelReestr = Reestr::publicTable($this->type, $this->groups, $this->departments, true, $this->periods);
		
		foreach ($modelReestr as $model)
		{
			
			// добавление группы
			$this->addGroup($this->reportData['data'], $model);
			
			// добавление реестра
			$this->addViolation($this->reportData['data'][$model['group_number']], $model);
			
			$this->addViolationData(
				$this->reportData['data'][$model['group_number']], 
				$this->periods, 
				$model['id'], 
				(count($this->ifns) == 1)
			);
		}
		return $this->reportData;
	}
	
	
	public function getReport()
	{
		return $this->reportData;
	}
	
	/**
	 * Добавление разделов
	 * @param array $arr
	 * @param unknown $model
	 */
	private function addGroup(&$arr, $model)
	{
		if (!isset($arr[$model['group_number']]))
		{		
			$arr[$model['group_number']]['group_number'] = $model['group_number'];
			$arr[$model['group_number']]['group_name'] = $model['group_name'];
		}
		
	}
	
	private function addViolation(&$arr, $model)
	{
		$index = $model['id']; 
		$arr[$index]['id_reestr'] 		= $model['id'];
		$arr[$index]['number_model'] 	= $model['number_model'];
		$arr[$index]['number_typical']	= $model['number_typical'];
		$arr[$index]['name_violation'] 	= $model['name_violation'];
		$arr[$index]['type_violation'] 	= $model['type_violation'];
		$arr[$index]['regulations'] 	= $model['regulations'];
	}
	
	private function addViolationData(&$arr, $periods, $modelId, $oneIfns)
	{
			
		$query = (new \yii\db\Query())
			->from('{{%data}} [data]')
			->where(Period::forSQL($periods, '[data]'))
			->andWhere('id_reestr=:id_reestr', [':id_reestr'=>$modelId])
			->all();
		
		foreach ($query as $q)
		{			 
			$arr [$modelId] ['ifns'] [$q['code_no']] [$q['period_year']] [$q['period']] ['doc_all'] 			= $q['doc_all'];
			$arr [$modelId] ['ifns'] [$q['code_no']] [$q['period_year']] [$q['period']] ['doc_violation'] 		= $q['doc_violation'];
			$arr [$modelId] ['ifns'] [$q['code_no']] [$q['period_year']] [$q['period']] ['doc_violation_irr'] 	= $q['doc_violation_irr'];
			$arr [$modelId] ['ifns'] [$q['code_no']] [$q['period_year']] [$q['period']] ['summ_violation'] 		= $q['summ_violation'];
			$arr [$modelId] ['ifns'] [$q['code_no']] [$q['period_year']] [$q['period']] ['exceeding_duration'] 	= $q['exceeding_duration'];
			// %
			if (is_numeric($q['doc_all']) && is_numeric($q['doc_violation']) && ($q['doc_all']>0))
			{
				$arr [$modelId] ['ifns'] [$q['code_no']] [$q['period_year']] [$q['period']] ['persent'] = 
					intval($q['doc_violation']) / intval($q['doc_all']) * 100;
			}
			
			$this->calculateViolationData($arr, $q);
			$this->calculateViolationData($arr, $q, $q['code_no']);
			
			if (!$oneIfns)
			{
				$this->calculateViolationData($arr[$modelId], $q);
			}
			
		}		
		ksort($arr);
	}
	
	
	
	private function calculateViolationData(&$arr, $q, $ifns='8600')
	{
		if (!isset($arr ['ifns'] [$ifns][$q['period_year']][$q['period']]))
		{
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_all'] 				= $q['doc_all'];
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_violation'] 			= $q['doc_violation'];
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_violation_irr'] 		= $q['doc_violation_irr'];
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['summ_violation'] 		= $q['summ_violation'];
		}
		else
		{
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_all'] 				+= intval($q['doc_all']);
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_violation'] 			+= intval($q['doc_violation']);
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_violation_irr'] 		+= intval($q['doc_violation_irr']);
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['summ_violation'] 		+= intval($q['summ_violation']);			
		}
		
		// %
		
		$docAll = $arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_all'];
		$docViolation = $arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['doc_violation'];
		
		if (is_numeric($docAll) && is_numeric($docViolation) && ($docAll>0))
		{
			$arr ['ifns'] [$ifns] [$q['period_year']] [$q['period']] ['persent'] = intval($docViolation) / intval($docAll) * 100;
		}
		
	}
	
	
	
	/**
	 * Получение списка доступных разделов пользователю
	 * Для пользователей с ролями admin и moderator возвращаются все группы
	 * @author oleg
	 * @return \yii\db\Query
	 */
	private function groups($filter) 
	{
		$queryGgroups = (new \yii\db\Query())
			->distinct(true)
			->select('[group].[id],[group].[number],[group].[name]')
			->from('{{%group}} [group]')
			->join('LEFT JOIN', '{{%reestr}} [reestr]', '[reestr].[id_group]=[group].[id]')
			->join('LEFT JOIN', '{{%link_reestr_department}} [link_reestr_department]', '[link_reestr_department].[id_reestr]=[reestr].[id]')
			->join('LEFT JOIN', '{{%department}} [department]', '[department].[id]=[link_reestr_department].[id_department]');
		
		if (!\Yii::$app->user->identity->inRole(['admin', 'moderator']))
		{
			$queryGgroups->andWhere(['[department].[AD_groupName]' => \Yii::$app->user->identity->ADRoles]);
		}
		
		if ($filter != null && count($filter))
		{
			$queryGgroups->andWhere(['[group].[id]' => $filter]);
		}
		
		return $queryGgroups->all();		
	}
	
	
	
	
	public static function model()
	{
		return new self();
	}
	
	
	/**
	 * Получение отчета
	 * @param array $period
	 * 		$period['begin']['mes'] - начало периода (месяц)
	 * 		$period['begin']['year'] - начало периода (год)
	 * 		$period['end']['mes'] - конец периода (месяц)
	 * 		$period['end']['year'] - конец периода (год)
	 *  
	 * @param unknown $condition
	 * 		$condition['ifns'] - инспекции, (если не указаны, то все)
	 * 		$condition['']
	 * 
	 * @return NULL
	 */
	public function find($period, $condition)
	{
		if (!$this->checkPeriod($period))
			return null;
	}
	
	
	
	
		
	
	private function checkPeriod($period)
	{		
		// isset
		if (!isset($period['begin']['mes']) || !isset($period['end']['mes']))
			return false;
		if (!isset($period['begin']['year']) || !isset($period['end']['year']))
			return false;
		
		// numeric
		if (!is_numeric($period['begin']['mes']) || !is_numeric($period['end']['mes']))
			return false;
		if (!is_numeric($period['begin']['year']) || !is_numeric($period['end']['year']))
			return false;
			
		return true;
	}
	
	
	/**
	 * Нарушуния для просмотра в отчете
	 * @param unknown $id
	 * @param unknown $period
	 * @param unknown $periodYear
	 * @param unknown $ifns
	 * @return \yii\data\ActiveDataProvider[]
	 */
	public static function violationModel($id, $period, $periodYear, $ifns)
	{
		
		$listIfns = \Yii::$app->user->identity->org_code;
		
		if (\Yii::$app->user->identity->isUfns)
		{
			if ($ifns=='8600')
			{
				$listIfns = ArrayHelper::map(Ifns::find()->all(), 'code_no', 'code_no');
			}
			else
			{
				$listIfns = $ifns;
			}
		}
				
		
		$queryDescription = Data::find();
		$dataProviderDescription = new ActiveDataProvider([
			'query' => $queryDescription,
			'sort' => false,
		]);
		$queryDescription->filterWhere([
			'id_reestr' => $id,
			'period' => $period,
			'period_year' => $periodYear,
			'code_no' => $listIfns,
		]);
		
		
		// file
		$queryFile = File::find();
		$dataProviderFile = new ActiveDataProvider([
			'query' => $queryFile,
			'sort' => false,
		]);
		$queryFile->joinWith('data data');
		$queryFile->filterWhere([
			'data.id_reestr' => $id,
			'data.period' => $period,
			'data.period_year' => $periodYear,
			'data.code_no' => $listIfns,
		]);
		
		
		return [
			'description' => $dataProviderDescription,
			'file' => $dataProviderFile,
		];
		
	}
	
	
	public function getListDepartment()
	{
		return ArrayHelper::map(Department::find()->orderBy('name asc')->all(), 'id', 'name');
	}
	
	
	public function getListGroup()
	{
		return ArrayHelper::map(Group::find()->orderBy('group_number asc')->all(), 'id', 'allName');
	}
	
	
	public function getListType()
	{
		return [
			'0' => 'Все',
			Reestr::TYPE_VIOLATION_TYPICAL => Reestr::TYPE_VIOLATION_DESCRIPTION_TYPICAL,
			Reestr::TYPE_VIOLATION_MODEL => Reestr::TYPE_VIOLATION_DESCRIPTION_MODEL,
		];
	}
	
}
