<?php

namespace app\models;

use Yii;
use app\components\BeforeSaveBehavior;
use app\components\AfterFindBehavior;

/**
 * This is the model class for table "ifns".
 *
 * @property string $code_no
 * @property string $name_no
 * @property integer $disable_no
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 *
 * @property Reestr[] $reestrs
 */
class Ifns extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ifns}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_no', 'name_no'], 'required'],
            [['code_no', 'name_no', 'log_change'], 'string'],
            [['disable_no', 'sort'], 'integer'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code_no' => 'Код НО',
            'name_no' => 'Наименование НО',
            'disable_no' => 'Не действующая НО',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
        	'sort' => 'Индекс сортировки',
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
     * @return \yii\db\ActiveQuery
     */
    public function getReestrs()
    {
        return $this->hasMany(Reestr::className(), ['code_no' => 'code_no']);
    }

    /**
     * @inheritdoc
     * @return IfnsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new IfnsQuery(get_called_class());
    }
    
    
        
}
