<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use app\components\AfterFindBehavior;

/**
 * This is the model class for table "{{%data_file}}".
 *
 * @property integer $id
 * @property integer $id_data
 * @property string $filename_generate
 * @property string $filename_original
 * @property string $date_create
 * @property string $author_name
 *
 * @property Data $idData
 */
class File extends \yii\db\ActiveRecord
{
	
	public $uploadFiles;
	private $errorsLoadFiles;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%data_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_data'], 'required'],
            [['id_data'], 'integer'],
            [['filename_generate', 'filename_original', 'author_name'], 'string'],
            [['date_create'], 'safe'],
            [['id_data'], 'exist', 'skipOnError' => true, 'targetClass' => Data::className(), 'targetAttribute' => ['id_data' => 'id']],
        	[['uploadFiles'], 'file', 'skipOnEmpty'=>false, 'maxFiles'=>10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'УН',
            'id_data' => 'УН Data',
            'filename_generate' => 'Имя файла',
            'filename_original' => 'Имя файла',
            'date_create' => 'Дата создания',
            'author_name' => 'Автор',
        	'uploadFiles' => 'Файлы',
        ];
    }
	
    
    /**
     * {@inheritDoc}
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
    	return [    		
    		[
    			'class' => AfterFindBehavior::className(),
    			'arrayDate' => [
    				['field'=>'date_create'],
    			],
    		],
    	];
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasOne(Data::className(), ['id' => 'id_data']);
    }
    
    
    /**
     * Генерирование имени файла
     * @return string
     */
    private function generateFileName()
    {
    	return uniqid('file_',true);
    }
    
    
    /**
     * Загрузка файлов
     * @return boolean
     */
    public function upload()
    {
    	$this->errorsLoadFiles = [];
    	
    	if ($this->validate())
    	{    		
    		$filePath = \Yii::$app->basePath . '\web\files\\' . $this->data->id_reestr . '\\' . $this->data->code_no . '\\';
    		$urlPath = '/files/' . $this->data->id_reestr . '/' . $this->data->code_no . '/';
    		
    		// проверка каталога/создание каталога
    		if (!file_exists($filePath))
    		{
    			if (!mkdir($filePath, null, true))
    			{
    				$this->errorsLoadFiles[] = 'Не удалось создать каталог "' . $filePath . '"!';
    				return false;
    			}
    		}    		    		    	
    		
    		if (!count($this->uploadFiles))
    		{
    			$this->errorsLoadFiles[] = 'Не выбраны файл(ы) для загрузки!';
    			return false;
    		}
    		
    		// обоработка всех файлов
    		foreach ($this->uploadFiles as $file)
    		{    		
    			$fileName = $this->generateFileName() . '.' . $file->extension;
    			 
    			if (file_exists(iconv('utf-8', 'windows-1251', $filePath . $fileName)))
    			{
    				$this->errorsLoadFiles[] = 'Файл ' . $fileName . ' уже существует!';
    				continue;
    			}
    			
    			// сохранение файлв в каталог
    			if (!$file->saveAs($filePath . $fileName))
    			{
    				$this->errorsLoadFiles[] = 'Произошла ошибка "' . $file->error . '" при сохранении файла ' . $file->name;
    				continue;
    			}
    			 
    			// сохранение файла в БД
    			try
    			{
    				if (!Yii::$app->db->createCommand()->insert('{{%data_file}}', [
    					'id_data' => $this->id_data,
    					'filename_generate' => $urlPath . $fileName,
    					'filename_original' => $file->name,
    					'author_name' => $this->author,
    				])->execute())
    				{
    					$this->errorsLoadFiles[] = 'Файл "' . $file->name . '" не был сохранен в БД!';
    				}    				
    			}
    			catch (Exception $ex)
    			{    				
    				$this->errorsLoadFiles[] = 'Произошла ошибка сохранения в БД "' . $ex->getMessage() . '" при сохранении файла ' . $file->name;
    			}    			    	
    		}    	
    		return !count($this->errorsLoadFiles);
    	}    	
    	return false;    
    }
    
    
    /**
     * Удаление файла
     * @param integer $id
     * @return boolean
     */
    public function deleteFile()
    {
    	    	
    	// проверка существования файла    	
    	if (!file_exists(\Yii::$app->basePath . $this->filename_generate))
    		return true;
    	
    	// удаленеие файла
    	if (!@unlink(\Yii::$app->basePath . $this->filename_generate))
    		return false;
    	
    	return true;
    	
    }
    
    
    /**
     * Массив ошибок (если есть)
     * @return array
     */
    public function getUploadErrors()
    {
    	return $this->errorsLoadFiles;
    }
    
    
    /**
     * Автор
     * @return string
     */
    public function getAuthor()
    {    	
    	$authorName = null;
    	$authorLogin = null;
    	
    	$authorLogin = (isset(\Yii::$app->user->identity->username) ? \Yii::$app->user->identity->username : null);
    	$authorName = (isset(\Yii::$app->user->identity->fio) ? \Yii::$app->user->identity->fio : null);
    	
    	if ($authorLogin===null && $authorName===null)
	    	return 'Гость ' . $_SERVER['REMOTE_ADDR'];
    	
    	return ($authorName!==null ? $authorName : 'Без имени') . ' (' . $authorLogin . ')';
    }
    
    /*
    public function getSize()
    {
    	if (!file_exists(\Yii::$app->basePath . $this->filename_generate))
    		return 0;
    	
    	
    }*/
    
}
