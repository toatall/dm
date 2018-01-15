<?php

use yii\base\Object;
use app\models\ReportReestr;

class ReportGroup extends Object
{
	
	private $reestrs;
	
	public $id;
	public $number;
	public $name;
	
	// номер и имя вопроса
	private function add($number, $reestr)
	{
		$this->reestrs[$number] = $reestr;		
	}
	
	public function findOne($number)
	{	
		if (isset($this->reestrs[$number]))
			return $this->reestrs[$number];
		return null;
	}
	
	public function findAll()
	{
		ksort($this->reestrs);
		return $this->reestrs;
	}
	
	
	public function calculate()
	{
		$calcReestr = new ReportReestr();		
		foreach ($this->reestrs as $reestr)
		{
			$this->plus($calcReestr, $reestr);
		}
	}
	
	
	private function plus(&$reestr1, $reestr2)
	{
		
	}
	
	
	public function getReestrs()
	{
		
	}
	
}