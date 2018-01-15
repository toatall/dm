<?php

namespace app\models;


use yii\base\Object;


class ReportReestr extends Object
{
	
	private $dataReestr;

	
	public function add($codeNo, $doc_all, $doc_violation, $doc_violation_irr, $summ_violation, $exceeding_duration)
	{
		$this->dataReestr[$codeNo] = [
			'doc_all' => $doc_all,
			'doc_violation' => $doc_violation,
			'doc_violation_irr' => $doc_violation_irr,
			'summ_violation' => $summ_violation,
			'exceeding_duration' => $exceeding_duration,
		];
		$this->calculate($codeNo);
	}
	
	
	private function calculate($codeNo)
	{
		// если есть 1 запись, то пропускаем
		// если 2 записи, то нужно их сложить
		// если больше 2х записей, то складывать новую с суммирующей
		if (count($this->dataReestr) > 1)
		{
			if (count($this->dataReestr) == 2)
			{
				foreach ($this->dataReestr as $array)
				{
					if (isset($this->dataReestr['8600']))
					{
						$this->dataReestr['8600']['doc_all'] 			+= $array['doc_all'];
						$this->dataReestr['8600']['doc_violation'] 		+= $array['doc_violation'];
						$this->dataReestr['8600']['doc_violation_irr'] 	+= $array['doc_violation_irr'];
						$this->dataReestr['8600']['summ_violation'] 	+= $array['summ_violation'];
					}
					else 
					{
						$this->dataReestr['8600']['doc_all'] 			= $array['doc_all'];
						$this->dataReestr['8600']['doc_violation'] 		= $array['doc_violation'];
						$this->dataReestr['8600']['doc_violation_irr'] 	= $array['doc_violation_irr'];
						$this->dataReestr['8600']['summ_violation'] 	= $array['summ_violation'];
					}					
				}			
			}
			else 
			{
				$this->dataReestr['8600']['doc_all'] 			+= $this->dataReestr[$codeNo]['doc_all'];
				$this->dataReestr['8600']['doc_violation'] 		+= $this->dataReestr[$codeNo]['doc_violation'];
				$this->dataReestr['8600']['doc_violation_irr'] 	+= $this->dataReestr[$codeNo]['doc_violation_irr'];
				$this->dataReestr['8600']['summ_violation'] 	+= $this->dataReestr[$codeNo]['summ_violation'];
			}			
		}
	}
	
	
	public function read($codeNo)
	{
		if (isset($this->dataReestr[$codeNo]))
		{
			return $this->dataReestr[$codeNo];
		}	
		return null;
	}
	
	
	public function readAll()
	{
		ksort($this->dataReestr);		
		return $this->dataReestr;
	}
	
	
}