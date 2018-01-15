<?php

namespace app\models;

use Yii;
use app\components\BeforeSaveBehavior;

/**
 * This is the model class for table "{{%data}}".
 *
 * @property integer $id
 * @property integer $id_reestr
 * @property string $code_no
 * @property integer $period_year
 * @property integer $doc_all
 * @property integer $doc_violation
 * @property integer $doc_violation_irr
 * @property string $summ_violation
 * @property string $exceeding_duration
 * @property string $node
 * @property integer $author_id
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property string $period
 *
 * @property Ifns $codeNo
 * @property Reestr $idReestr
 * @property User $author
 * 
 */
class Data extends \yii\db\ActiveRecord
{
	
	public $isPost = false;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_reestr', 'code_no', 'period', 'period_year', 'doc_all', 'doc_violation', 'doc_violation_irr'], 'required'],
            [['id_reestr', 'period_year', 'doc_all', 'doc_violation', 'doc_violation_irr', 'author_id'], 'integer'],
            [['period', 'code_no', 'exceeding_duration', 'node', 'log_change'], 'string'],
            [['summ_violation'], 'number'],            
            [['code_no'], 'exist', 'skipOnError' => true, 'targetClass' => Ifns::className(), 'targetAttribute' => ['code_no' => 'code_no']],
            [['id_reestr'], 'exist', 'skipOnError' => true, 'targetClass' => Reestr::className(), 'targetAttribute' => ['id_reestr' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        	[['file'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_reestr' => 'Реестр нарушений',
            'code_no' => 'Код НО',
        	'period' => 'Период (тип)',          
            'period_year' => 'Период (год)',
            'doc_all' => 'Всего документов',
            'doc_violation' => 'Документов, по которым выявлены нарушения',
            'doc_violation_irr' => 'Количество неустраненных нарушений',
            'summ_violation' => 'Сумма нарушений, тыс.руб.',
            'exceeding_duration' => 'Превышение срока',
            'node' => 'Примечание (ссылка на приложения)',
            'author_id' => 'Автор',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
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
    	];
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodeNo()
    {
        return $this->hasOne(Ifns::className(), ['code_no' => 'code_no']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReestr()
    {
        return $this->hasOne(Reestr::className(), ['id' => 'id_reestr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
    
    
    public function getFile()
    {
    	return $this->hasMany(File::className(), ['id_data'=>'id']);
    }
    
    
    /**
     * Поиск показателей и создание модели
     * @param unknown $id_reestr
     * @param unknown $code_no
     * @param unknown $periodType
     * @param unknown $periodValue
     * @param unknown $periodYear
     * @return \app\models\Data
     */
    public static function loadCreateData($id_reestr, $code_no, $periodType, $periodYear)
    {
    	$model = self::find()->where('id_reestr=:id_reestr and code_no=:code_no and period=:period_type and period_year=:period_year', [
    		':id_reestr' => $id_reestr,
    		':code_no' => $code_no,
    		':period_type' => $periodType,    		
    		':period_year' => $periodYear,
    	])->one();
    	if ($model===null)
    		$model = new self();
    	
    	return $model;
    }
           
}
