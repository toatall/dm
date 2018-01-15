<?php
	
namespace app\helpers;



/**
 * Получение информации о пользователе в Active Directory
 * по учетной записи пользователя
 *  
 * 
 * @author oleg <trusov@r86.nalog.ru>
 * @version 02.03.2016 - create, 01.03.2017 - refactoring
 */
class AD 
{
	
	
	/**	 
	* @param $username string - имя пользователя
	* @return array|false
	* @author oleg
	*/
	public static function getInfoByLogin($username)
	{
		$params = require(__DIR__ . '/../config/AD_params.php');
		
		$current_user = $username;

		$ldap_connect = @ldap_connect($params['server'], $params['port']);			
		@ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);
		if ($ldap_connect)
		{
			$ldap_bind = @ldap_bind($ldap_connect, $params['login'], $params['password']);
			if ($ldap_bind)
			{
				if ($ldap_search = @ldap_search($ldap_connect, $params['baseDn'],
						'(sAMAccountName='.$current_user.')'))
				{
					$res = @ldap_get_entries($ldap_connect, $ldap_search);
					return (count($res) > 0) ? $res[0] : null;
				}
			}			
		}
		
		return null;
	}
	
}