<?php

namespace app\models;

use Yii;
use app\components\BeforeSaveBehavior;
use app\components\AfterFindBehavior;
use app\models\Period;


/**
 * This is the model class for table "{{%reestr}}".
 *
 * @property integer $id
 * @property integer $id_group
 * @property integer $id_department
 * @property string $number_model
 * @property string $number_typical
 * @property string $name_violation
 * @property string $regulations
 * @property string $period
 * @property string $period_dop
 * @property string $description
 * @property string $date_create
 * @property string $date_edit
 * @property string $date_delete
 * @property string $log_change
 * @property integer $type_violation
 * 
 * @property string cacheDepartments
 *
 * @property Data[] $datas
 */
class Reestr extends \yii\db\ActiveRecord
{
	
	const TYPE_VIOLATION_MODEL = 2;
	const TYPE_VIOLATION_TYPICAL = 1;
	const TYPE_VIOLATION_DESCRIPTION_MODEL = 'Перечень вопросов, не отнесенных к перечню типичных (системных) нарушений';
	const TYPE_VIOLATION_DESCRIPTION_TYPICAL = 'Перечень вопросов по типичным (системным) нарушениям';
		
	
	private $cacheDepartments;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reestr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_group', 'type_violation', 'period'], 'required'],
            [['id_group', 'id_department', 'type_violation'], 'integer'],
            [['period', 'number_model', 'number_typical', 'name_violation', 'regulations', 'period_dop', 'description', 'log_change'], 'string'],
            [['departments'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_group' => 'Раздел',
        	'id_department' => 'Отдел',
        	'departmentsText' => 'Отделы',
        	'departments' => 'Отделы',
            'number_model' => '№ пункта (типовой программы)',
            'number_typical' => '№ пункта (типичного нарушения)',        	
            'name_violation' => 'Краткое описание',            
            'regulations' => 'Нормативные документы',
            'period' => 'Период',
        	'periodTypeName' => 'Период',
            'period_dop' => 'Период (доп)',
            'description' => 'Примечание',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'log_change' => 'Журнал изменений',
        	'type_violation' => 'Тип вопроса',
        ];
    }
    
    
    /**
     * {@inheritDoc}
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
    	return [
    		'class' => BeforeSaveBehavior::className(),
    		[
    			'class' => AfterFindBehavior::className(),
    			'arrayDate' => [
    				['field'=>'date_create'],
    				['field'=>'date_edit'],
    				['field'=>'date_delete'],
    			],
    		],
    	];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatas()
    {
        return $this->hasMany(Data::className(), ['id_reestr' => 'id']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
    	return $this->hasOne(Group::className(), ['id' => 'id_group']);
    }
	
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
    	return $this->hasOne(Department::className(), ['id' => 'id_department']);    	
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
    	return $this->hasMany(Department::className(), ['id' => 'id_department'])->viaTable('{{%link_reestr_department}}', ['id_reestr'=>'id']);
    }
    
    
    /**
     * Setter. Сохранение отделов
     * @param array $departments
     */
    
    public function setDepartments($departments)
    {    	    	
    	if (!is_array($departments))
    		$departments[] = $departments;
    	
		if ($this->isNewRecord && $this->id == null)
		    return;
    		
    	// удаление связей
    	Yii::$app->db->createCommand()
    		->delete('{{%link_reestr_department}}', 'id_reestr=:id', [':id'=>$this->id])->execute();
    		 
    	// добавление связей
    	foreach ($departments as $dep)
    	{
    		Yii::$app->db->createCommand()
    			->insert('{{%link_reestr_department}}', [
    				'id_department' => $dep,
    				'id_reestr' => $this->id,
    		])->execute();
    	}
    	
    }
    
    
    
    /**
     * @inheritdoc
     * @return ReestrQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReestrQuery(get_called_class());
    }
    
    
    /**
     * Виды вопросов (типовое, типичное нарушение)
     * @return string[]
     */
    public static function typeList()
    {
    	return [
    		1 => 'Типичное',
    		2 => 'Типовое',
    	];
    }
    
    /**
     * Наименование типа периода
     * @return string
     */
    public function getPeriodTypeName()
    {
    	return Period::periodValueByKey($this->period);
    }
    
    
    /**
     * Список отделов
     * @return string
     */
    public function getDepartmentsText()
    {
    	$dep = $this->departments;
    	$res = '';
    	foreach ($dep as $d)
    	{
    		$res .= $d->name . '</br>';
    	}
    	return $res;
    }
    
    
    /**
     * Сохранение связи отделов
     * @param array $departments
     */
    public function saveRelationDepartment($departments)
    {
        $this->cacheDepartments = $departments;
        
    	if (!is_array($departments))
    		$departments[] = $departments;
    	
    	if ($this->isNewRecord && $this->id == null)
    	    return;
    		
    	// удаление связей
    	Yii::$app->db->createCommand()
    		->delete('{{%link_reestr_department}}', 'id_department=:id', [':id'=>$this->id]);
    	
    	// добавление связей
    	foreach ($departments as $dep)
    	{
    		Yii::$app->db->createCommand()
    			->insert('{{%link_reestr_department}}', [
    				'id_department' => $dep,
    				'id_reestr' => $this->id,
    			]);
    	}
    }
	
    
    /**
     * Реестр нарушений для построения таблицы на главное странице
     * @return Reestr
     */
    public static function publicTable($type=null, $group=null, $department=null, $onlyData=false, $periods=[])
    {
	
    	$reestrQuery = (new \yii\db\Query())
    		->distinct(true)
    		->select('
    				[view_reestr].[group_number], 
    				[view_reestr].[group_name], 
    				[view_reestr].[id], 
    				[view_reestr].[id_group],     				
    				[view_reestr].[number_model], 
    				[view_reestr].[number_typical], 
    				[view_reestr].[name_violation], 
    				[view_reestr].[regulations], 
    				[view_reestr].[period], 
    				[view_reestr].[type_violation],
    				[view_reestr].[departments]')
    		->from('{{%view_reestr}} [view_reestr]')    		
    		->orderBy('[view_reestr].[group_number] asc, [view_reestr].[type_violation] desc, [view_reestr].[number_model] asc, [view_reestr].[number_typical] asc');
    	
    	if (!\Yii::$app->user->identity->inRole(['admin', 'moderator']) && \Yii::$app->user->identity->isUfns)
    	{
    		$reestrQuery->andWhere(['[AD_groupName]' => \Yii::$app->user->identity->ADRoles]);
    	}
    	
    	if ($onlyData)
    	{
    		$reestrQuery->join('LEFT JOIN', '{{%data}} [data]', '[data].[id_reestr]=[view_reestr].[id]');
    		$reestrQuery->andWhere(Period::forSQL($periods, '[data]'));
    		$reestrQuery->andWhere(['is not', '[data].[doc_all]', null]);
    	}
    	
    	if ($type!==null && $type!=0)
    	{
    		$reestrQuery->andWhere(['type_violation'=>$type]);
    	}
    	
    	if ($group!==null && $group!=0)
    	{
    		$reestrQuery->andWhere(['id_group'=>$group]);
    	}
    	
    	if ($department!==null && $department!=0)
    	{
    		$reestrQuery->andWhere(['id_department'=>$department]);
    	}
    	
    	return $reestrQuery->all();
    	    	
    }
    
        
    /**
     * Проверка прав пользователя на текущий раздел
     * @param string $condition
     * @return boolean
     */
    public static function accessToReestrId($condition)
    {
    	
    	if (\Yii::$app->user->identity->inRole(['admin', 'moderator']))
    	{
    		return true;
    	}
    	
    	return $model = (new \yii\db\Query())
    		->from('{{%reestr}} [r]')
    		->join('JOIN', '{{%link_reestr_department}} [lnk]', '[r].[id]=[lnk].[id_reestr]')
    		->join('JOIN', '{{%department}} [d]', '[d].[id]=[lnk].[id_department]')
    		->where(['[d].[AD_groupName]' => \Yii::$app->user->identity->ADRoles])
    		->andWhere(['[r].[id]'=>$condition])
    		->exists();
    }
    
    
    /**
     * Отделы (кэш)
     * @return string
     */
    public function getCacheDepartments()
    {
        return $this->cacheDepartments;        
    }
    
}
