<?php

namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;


class AfterFindBehavior extends Behavior
{
	
	
	public $arrayDate = [];
	
	const FRMAT_DATE_DEFAULT = 'd.m.Y H:i:s';
	
	/**
	 * {@inheritDoc}
	 * @see \yii\base\Behavior::events()
	 */
	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_FIND => 'find',				
		];
	}
	
	
	
	public function find($event)
	{
		foreach ($this->arrayDate as $date)
		{
			if (!isset($date['field']))
				continue;
			
			$format = (isset($date['format']) ? $date['format'] : self::FRMAT_DATE_DEFAULT);
				
			if ($this->owner->hasProperty($date['field']) && $this->owner->$date['field'] !== null)
			{
				$this->owner->$date['field'] = date($format, strtotime($this->owner->$date['field']));
			}
		}
		
	}
	
	
}
