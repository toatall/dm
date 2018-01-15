<?php

namespace app\models;

/**
 * @todo add AD information user
 */
use yii\db\ActiveRecord;
use app\helpers\AD;
use yii\helpers\ArrayHelper;


/**
 * Пользователь
 * 
 * @property username string
 * @property fio string
 * @property org_code string
 * @property date_create string 
 * 
 * @author oleg
 * @version 27.03.2017
 *
 */

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    
    public $password;
    public $authKey;
    public $accessToken;
    public $rolesAD;
    
    public static $infoAD;
        
    
    public static function remoteLogin()
    {
    	return self::explodeUserName();
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
    	return '{{%user}}';
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
    	return [
    		[['username'], 'required'],
    		[['username'], 'string'],
    	];
    }
    
    
    /**
     * Извленение имени пользователя из переменной $_SERVER
     * и получение имени без учета домена (без REGIONS)
     * @return NULL|string
     * @author oleg
     * @version 27.03.2017
     */
    private function explodeUserName()
    {    	
    	$username = null;
    	
    	if (isset($_SERVER['REMOTE_USER']))
    	{
    		$ex = explode('\\',$_SERVER['REMOTE_USER']);
    		if (count($ex) > 0)
    			$username = $ex[1];    	
    	}
    	
    	return $username;    	
    }
    
    
    /**
     * Извлечение кода организации из учетной записи пользователя (86XX_NNN_NNNNNN, 86XX-XX-XXX...)
     * 
     * @param string $username
     * @return string|NULL
     * @author oleg
     * @version 27.03.2017
     */
    private static function explodeOrgCode($username)
    {
		if (strlen($username)>=4)
		{
			$s = substr($username, 0, 4);
			if (is_numeric($s))
			{				
				// @todo полумать над проверкой кода организации в таблице
				return $s;
			}
		}
		
		return null;
    }
    
    
    private static function infoAD($username)
    {
    	self::$infoAD = AD::getInfoByLogin($username);
    }
	
    
    /**
     * Создание нового пользователя (который выполнил вход)
     * @return \app\models\User
     */
    private static function newUser()
    {    	
    	// получение информации из AD
    	$username = self::remoteLogin();
    	if ($username === null) return null;
    	
    	//$infoAD = AD::getInfoByLogin($username);
    	$infoAD = self::$infoAD;
    	
    	$model = new self;
    	$model->username = $username;
    	if ($infoAD!==null)
    	{
    		$model->fio = isset($infoAD['displayname'][0]) ? $infoAD['displayname'][0] : '';
    	}
    	$model->org_code = self::explodeOrgCode($username);
    	
    	if ($model->save())
    	{
	    	$model->password = null;
	    	$model->authKey = 'keyUser' . $username;
	    	$model->accessToken = $model->id . '-token';
	    	$model->rolesAD = $infoAD;
	    	return $model;
    	}

    	return null;
    }
    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {        	
    	$model = self::findOne(['id'=>$id]);
    	
    	if ($model === null)
    		return self::newUser();
    	
    	return $model;
    }

    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {       
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	
    	self::infoAD($username);
    	
    	$model = self::findOne(['username'=>$username]);
    	 
    	if ($model === null)
    		return self::newUser();
    	
    	$model->rolesAD = self::$infoAD;
    		
    	return $model;
    }
    
   
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    
    
    /**
     * Проверка присутствия текущего пользователя
     * в роли(ях) $mixed
     * @param array $mixed
     * @return bool
     */
    public static function inRole($mixed)
    {
    	if (!is_array($mixed))
    		$mixed = array($mixed);
    	
    	return (new \yii\db\Query())
    		->from('{{%user_role}}')
    		->where('id_user=:id', [':id'=>\Yii::$app->user->id])
    		->andWhere(array('IN', 'rolename', $mixed))
    		->exists();
    }
    
    
    /**
     * Список ролей текущего пользователя
     * 
     * @return array
     * @author oleg
     * @version 27.03.2017
     */
    public static function getRoles()
    {
    	return ArrayHelper::map(Role::findAll('id_user=:id_user', [':id_user'=>Yii::$app->user->id]), 'rolename', 'rolename');
    }
    
    
    
    /**
     * Роли, в которые включен пользователь в ActiveDirectory
     * @return array
     */
    public function getADRoles()
    {
    	$resArray = [];
    	$adRoles = isset(\Yii::$app->session->get('dm88_infoAD')['memberof']) ? \Yii::$app->session->get('dm88_infoAD')['memberof'] : [];
    	foreach ($adRoles as $role)
    	{
    		$ldapExplode = ldap_explode_dn($role, 1);
    		if (isset($ldapExplode[0]))
    			$resArray[] = $ldapExplode[0];
    	}
    	return $resArray;
    }
   
    
    
    public function getIsUfns()
    {
    	return ($this->org_code === '8600');
    }
    
}
