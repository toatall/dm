<?php

namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class BeforeSaveBehavior extends Behavior
{
	
	/**
	 * {@inheritDoc}
	 * @see \yii\base\Behavior::events()
	 */
	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'save',
			ActiveRecord::EVENT_BEFORE_UPDATE => 'save',
		];	
	}
	
	
	public function save($event)
	{	
		
		if (property_exists($this->owner, 'noBeforeSave') && $this->owner->noBeforeSave)
			return;
		
		// дата изменения
		if (!$this->owner->isNewRecord)
		{
			// дата изменения
			if ($this->owner->hasProperty('date_edit') && (!$this->owner->hasProperty('date_delete') || $this->owner->date_delete==null))
			{
				$this->owner->date_edit = new Expression('getdate()');
			}
		}
		
		// журнал изменений
		if ($this->owner->hasProperty('log_change'))
		{
			if ($this->owner->isNewRecord)
			{
				$this->owner->log_change = $this->getChange($this->owner->log_change, 'создание');
			}
			else if ($this->owner->hasProperty('date_delete') && $this->owner->date_delete != null)
			{
				$this->owner->log_change = $this->getChange($this->owner->log_change, 'удаление');
			}
			else
			{
				$this->owner->log_change = $this->getChange($this->owner->log_change, 'изменение');
			}
		}		
	}
	
	// получение даты, события и имени пользователя для журнала изменений
	private function getChange($lastChange, $message)
	{
		return $lastChange . '$' . date('d.m.Y H:i:s') . '|' . $message . '|' . (\Yii::$app->user->isGuest ? 'Гость' : \Yii::$app->user->identity->username);
	}
	
	
}