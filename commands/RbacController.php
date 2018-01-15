<?php

namespace yii\commands;

use Yii;
use yii\console\Controller;

/**
 * Создание и назначение ролей и прав 
 * @author oleg
 * @version 27.03.2017
 *
 */
class RbacController extends Controller
{
	
	public function actionInit()
	{
		
		$auth = Yii::$app->authManager;
		
		//$auth->removeAll();
		
		// create roles
		$roleAdmin = $auth->createRole('admin');
		$roleModerator = $auth->createRole('moderator');
		$roleUFNS = $auth->createRole('UFNS');
		$roleIFNS = $auth->createRole('IFNS');
		
		// назначение ролей пользователям
		
	}
	
	
}