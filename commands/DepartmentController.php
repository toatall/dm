<?php
/**
 * Update departments name from ActiveDirectory
 * @author oleg
 * @version 02.05.2017
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Department;
use app\components\ADFindComponent;


class DepartmentController extends Controller
{
   
	/**
	 * Обновление наименований отделов из ActiveDirectory
	 */
	public function actionRefresh()
	{
		// список отделов
		$modelDepartments = Department::find()->all();
		
		foreach ($modelDepartments as $model)
		{
			$ldapDepartmentName = $this->ldapName($model->AD_distinguishedName);
			if ($ldapDepartmentName !== null && $ldapDepartmentName != $model->name)
			{
				$model->name = $ldapDepartmentName;
				$model->noBeforeSave = true;
				$model->save();
			}
		}
		
	}
	
	
	private function ldapName($distinguishedName)
	{
		$ldap = new ADFindComponent();
		$ldap_name = $ldap->getFolderDescription($distinguishedName);
		if (!empty(($ldap_name)))
		{
			return $ldap_name;
		}
		
		return null;
		
	}
	
}
