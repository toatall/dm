<?php

namespace app\models;

use Yii;
use app\components\BeforeSaveBehavior;
use app\components\AfterFindBehavior;
use app\components\ADFindComponent;

/**
 * This is the model class for table "{{%department}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $AD_distinguishedName
 * @property string $AD_groupName
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
   //	'ldap_description' => 'Описание',
 */
class Department extends \yii\db\ActiveRecord
{
	
	public $noBeforeSave = false;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%department}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AD_distinguishedName', 'AD_groupName'], 'required'],
            [['AD_distinguishedName', 'AD_groupName', 'log_change', 'name'], 'string'],           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
        	'name' => 'Наименование отдела',
            'AD_distinguishedName' => 'ActiveDirectory путь к каталогу',
            'AD_groupName' => 'ActiveDirectory группа доступа',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата имзенения',
            'log_change' => 'Журнал изменений',
      
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
    			],
    		],
    	];
    }

    /**
     * @inheritdoc
     * @return DepartmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepartmentQuery(get_called_class());
    }
    
    
    public static function findAccess()
    {
    	if (User::inRole(['admin', 'moderator']))
    		return self::find();
    	return self::find()->where(['AD_groupName' => \Yii::$app->user->identity->ADRoles]);
    }
    
    
   
}
