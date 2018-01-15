<?php

namespace app\models;

use Yii;
use app\components\AfterFindBehavior;
use app\components\BeforeSaveBehavior;
use yii\db\Query;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $group_number
 * @property string $group_name
 * @property string $date_create
 * @property string $date_edit
 * @property string $date_delete
 * @property string $log_change
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_number', 'group_name'], 'required'],
        	[['group_number'], 'integer'],
            [['group_name', 'log_change'], 'string'],          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'group_number' => 'Номер раздела',
            'group_name' => 'Наименование раздела',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'log_change' => 'Журнал изменений',
        	'allName' => 'Наименование раздела',
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
     * @inheritdoc
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
    }
    
    
    /**
     * Полное имя раздела
     * @return string
     */
    public function getAllName()
    {
    	return $this->group_number . '. ' . $this->group_name;
    }
    
    
    /**
     * Все группы, доступные пользователю
     * @return Query
     */
    public static function findByAccess()
    {
    	if (User::inRole(['moderator', 'admin']))
    		return self::find();
    	
    	return (new yii\db\Query())
    		->distinct(true)
    		->select('[group].[id], cast([group].[group_number] as varchar) + \'. \' + [group].[group_name] [allName]')
    		->from('{{%group}} [group]')
    		->join('LEFT JOIN', '{{%reestr}} [reestr]', '[reestr].[id_group]=[group].[id]')
    		->join('LEFT JOIN', '{{%link_reestr_department}} [link_reestr_department]', '[link_reestr_department].[id_reestr]=[reestr].[id]')
    		->join('LEFT JOIN', '{{%department}} [department]', '[department].[id]=[link_reestr_department].[id_department]')
    		->where(['[department].[AD_groupName]' => \Yii::$app->user->identity->ADRoles]);
    }
    
}
