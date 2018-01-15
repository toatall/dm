<?php

namespace app\components;

use yii\base\Object;


/**
 * Компонент получения информации из ActiveDirectory
 * @author oleg
 * @version 29.03.2017
 *
 */
class ADFindComponent extends Object
{
	
	// идентификатор соедниения с сервером ldap
	private $_ldapConnect = false;	
	
	// параметры для соединения с ldap
	private $config;
	
	
	/**
	 * Соединение с сервером ldap
	 */
	public function __construct()
	{
		$this->config = require(__DIR__ . '/../config/AD_params.php');
		$this->_ldapConnect = @ldap_connect($this->config['server'], $this->config['port']);
		
		if ($this->_ldapConnect)
		{
			@ldap_set_option($this->_ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
			@ldap_bind($this->_ldapConnect, $this->config['login'], $this->config['password']);
		}
	}
	
		
	/**
	 * Проверка соединения
	 * @return boolean
	 */
	public function getConnected()
	{
		return ($this->_ldapConnect != false ? true : false);
	}
	
	
	/**
	 * Получить описание раздела (organizationUnit)
	 * @param string $dn
	 * @return NULL|array
	 */
	public function getFolderDescription($dn)
	{
		if (!$this->getConnected()) return null;
		
		if ($ldapRead = @ldap_read($this->_ldapConnect, $dn, '(objectClass=organizationalUnit)'))
		{			
			if ($ent = @ldap_get_entries($this->_ldapConnect, $ldapRead))
			{							
				if (isset($ent[0]['description'][0]))
					return $ent[0]['description'][0];
			}
			
		}
		return null;
	}
	
	/**
	 * Вернуть ошибку ldap
	 * @return string
	 */
	public function error()
	{
		return ldap_errno($this->_ldapConnect) . ' ' . ldap_error($this->_ldapConnect);
	}
	
	
}