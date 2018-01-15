<?php

namespace app\models;

use yii\base\Model;


/**
 * Класс периоды
 * @author oleg
 * @version 03.04.2017
 */
class Period extends Model
{
	
	private static $_attr = [
		'MES' => 'ежемесячно',
		'KV' => 'ежеквартально',
		'POL' => 'полугодие',
		'9MES' => '9 месяцев',
		'GOD' => 'год',
	];
	
	// периоды
	private static $_per = [
		
		// месяцы
		'MES' => [
			'MES01' => 'январь',
			'MES02' => 'февраль',
			'MES03' => 'март',
			'MES04' => 'апрель',
			'MES05' => 'май',
			'MES06' => 'июнь',
			'MES07' => 'июль',
			'MES08' => 'август',
			'MES09' => 'сентябрь',
			'MES10' => 'октябрь',
			'MES11' => 'ноябрь',
			'MES12' => 'декабрь',
		],
			
		// кварталы
		'KV' => [
			'KV1' => '1 квартал',
			'KV2' => '2 квартал',
			'KV3' => '3 квартал',
			'KV4' => '4 квартал',
		],
			
		// полугодие
		'POL' => [
			'POL1' => '1 полугодие',
			'POL2' => '2 полугодие',
		],
		
		'9MES' => [
			'9MES' => '9 месяцев',
		],
			
		// годовая
		'GOD' => [
			'GOD' => 'год',
			'KV2' => 'ежегодно 2 квартал',
			'KV3' => 'ежегодно 3 квартал',
		],
			
	];
		
	
	/**
	 * Список всех доступных периодов для типа MES 
	 * @return array
	 */
	public static function periodMesAll()
	{
		return self::$_per['MES'] + self::$_per['KV'] + self::$_per['POL'] + self::$_per['9MES'] + self::$_per['GOD'];
	}
	
	
	/**
	 * Список всех доступных периодов для типа KV
	 * @return array
	 */
	public static function periodKvAll()
	{
		return self::$_per['KV'] + self::$_per['POL'] + self::$_per['9MES'] + self::$_per['GOD'];
	}
	
	/**
	 * Список всех доступных периодов для типа POL
	 * @return array
	 */
	public static function periodPolAll()
	{
		return self::$_per['POL'] + self::$_per['9MES'] + self::$_per['GOD'];
	}
	
	
	/**
	 * Список всех доступных периодов для типа GOD
	 * @return array
	 */
	public static function periodGodAll()
	{
		return self::$_per['GOD'];
	}
	
	
	
	/**
	 * Массив периодов по типу периода
	 * @param $type - значение типа периода
	 * @return array|null
	 */
	public static function periodsByType($type)
	{	
		switch ($type)
		{
			case 'MES':  { return self::periodMesAll(); }
			case 'KV': { return self::periodKvAll(); }
			case 'POL': { return self::periodPolAll(); }
			case 'GOD': { return self::periodGodAll(); }
		}
		return null;
	}
	
	

	/**
	 * Список годов
	 * @return number[]|string[]
	 */
	public static function periodYears()
	{
		$currentYear = date('Y');
		return [
			($currentYear-1) => ($currentYear-1),
			($currentYear) => ($currentYear),
			($currentYear+1) => ($currentYear+1),
		];
	}
	
	
	public static function fixedYears($startYear=2017)
	{
		$resultArray = [];
		do 
		{
			$resultArray[$startYear] = $startYear;
			$startYear++;
		}
		while ($startYear <= (date('Y')+1));
		
		return $resultArray;
	}
	
	/**
	 * Проверка корректности периода
	 * @param string $periodType
	 * @param integer $periodValue
	 * @param integer $periodYear
	 * @return boolean
	 */
	public static function checkPeridCorrect($periodType, $periodYear)
	{			
		if ($periodType !== null && !isset(self::periodMesAll()[$periodType]))
			return false;
		if ($periodYear !== null && !in_array($periodYear, self::periodYears()))
			return false;
		
		return true;
	}
	
	
	/**
	 * 
	 * @param array $periodOptions
	 * 		[begin][mes]
	 * 		[begin][year]
	 * 		[end][mes]
	 * 		[end][year]
	 * 		[use]
	 * 
	 * @return string
	 */
	public static function listPeriodByDates($periodOptions)
	{
		if (!self::checkPeriod($periodOptions))
			return [];
		
		
		$date1 = new \DateTime('01.' . $periodOptions['beginMes'] . '.' . $periodOptions['beginYear']);
		$date2 = new \DateTime('01.' . $periodOptions['endMes'] . '.' . $periodOptions['endYear']);
				
		if ($date1 > $date2) return [];
		
		$interval = new \DateInterval('P1M');
		$datePeriod = new \DatePeriod($date1, $interval, $date2);
		$aResult = array();
		
		$lastMES = $lastKV = $lastPOL = $last9MES = $lastGOD = $lastGODKV = null;
		
		foreach ( $datePeriod as $dt )
		{
			$y = $dt->format('Y');
			$m = $dt->format('m');
			
			if (!(isset($periodOptions['use']) && !(in_array('MES', $periodOptions['use']))))
			{
				
				$mPrevios = $m-1;
				$mPrevios = (strlen($mPrevios)==1 ? '0' . $mPrevios : $mPrevios);
				
				$aResult[] = [
					'year'=>$y, 
					'period'=>'MES'.$m, 
					'periodName'=>self::$_per['MES']['MES'.$m],
					'previos'=>$lastMES,					
				];
				$lastMES = count($aResult)-1;
											
			}
			
			// кварталы
			if (($k = $m % 3 == 0) & (!(isset($periodOptions['use']) && !(in_array('KV', $periodOptions['use'])))))
			{			
				$aResult[] = [
					'year'=>$y,
					'period'=>'KV' . ($m/3),
					'periodName'=>self::$_per['KV']['KV' . ($m/3)],
					'previos'=>$lastKV,
				];
				$lastKV = count($aResult)-1;
			}
			
			/*
			// годовые по кварталам
			if (($k = $m % 3 == 0) & (!(isset($periodOptions['use']) && !(in_array('GOD_KV', $periodOptions['use'])))))
			{
				if (isset(self::$_per['GOD']['GOD_KV' . ($m/3)]))
				{
					$aResult[] = [
						'year'=>$y,
						'period'=>'KV' . ($m/3),
						'periodName'=>self::$_per['GOD']['GODKV' . ($m/3)],
						'previos'=>$lastGODKV,
					];
					$lastGODKV = count($aResult)-1;
				}
			}
			*/
			
			// полугодие
			if (($k = $m % 6 == 0) & (!(isset($periodOptions['use']) && !(in_array('POL', $periodOptions['use'])))))
			{
				$aResult[] = [
					'year'=>$y,
					'period'=>'POL' . ($m/6),
					'periodName'=>self::$_per['POL']['POL' . ($m/6)],
					'previos'=>$lastPOL,
				];
				$lastPOL = count($aResult)-1;
			}
			
			// 9 месяцев
			if (($m == 9) & (!(isset($periodOptions['use']) && !(in_array('9MES', $periodOptions['use'])))))
			{
				$aResult[] = [
					'year'=>$y,
					'period'=>'9MES',
					'periodName'=>self::$_per['9MES']['9MES'],
					'previos'=>$last9MES,
				];
				$last9MES = count($aResult)-1;
			}
			
			
			// год
			if (($m == 12) & (!(isset($periodOptions['use']) && !(in_array('GOD', $periodOptions['use'])))))
			{
				$aResult[] = [
						'year'=>$y,
						'period'=>'GOD',
						'periodName'=>self::$_per['GOD']['GOD'],
						'previos'=>$lastGOD,
				];
				$lastGOD = count($aResult)-1;
			}
			
				
		}
		
		return $aResult;
	}
	
	/*
	public static function listPeriodByDates($periodOptions)
	{
		if (!self::checkPeriod($periodOptions))
			return [];
	
	
			$date1 = new \DateTime('01.' . $periodOptions['beginMes'] . '.' . $periodOptions['beginYear']);
			$date2 = new \DateTime('01.' . $periodOptions['endMes'] . '.' . $periodOptions['endYear']);
	
			if ($date1 > $date2) return [];
	
			$interval = new \DateInterval('P1M');
			$datePeriod = new \DatePeriod($date1, $interval, $date2);
			$aResult = array();
	
			foreach ( $datePeriod as $dt )
			{
				if (!(isset($periodOptions['use']) && !(in_array('MES', $periodOptions['use']))))
				{
					$m = $dt->format('m');
					$mPrevios = $m-1;
					$mPrevios = (strlen($mPrevios)==1 ? '0' . $mPrevios : $mPrevios);
					$y = $dt->format('Y');
					$aResult[$y]['MES' . $m] = self::$_per['MES']['MES' . $m];
	
					if ($m == 1)
					{
						if (isset($aResult[($y-1)]) && in_array('MES12', $aResult[($y-1)]))
						{
							$aResult[$y]['previos']['MES01'] = ['year'=>($y-1), 'period'=>'MES12'];
						}
					}
					else
					{
						if (isset($aResult[$y]) && in_array('MES' . $mPrevios, $aResult[$y]))
						{
							$aResult[$y]['previos']['MES'. $m] = ['year'=>$y, 'period'=>'MES' . $mPrevios];
						}
					}
				}
					
					
				switch ($dt->format('m'))
				{
					case 3:
						{
							if (!(isset($periodOptions['use']) && !(in_array('KV', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['KV1'] = self::$_per['KV']['KV1']; // 1 кв.
								self::previosKV('KV1', $dt->format('Y'), $aResult);
							}
							break;
						}
					case 6:
						{
							if (!(isset($periodOptions['use']) && !(in_array('KV', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['KV2'] = self::$_per['KV']['KV2']; // 2 кв.
								self::previosKV('KV2', $dt->format('Y'), $aResult);
							}
	
							if (!(isset($periodOptions['use']) && !(in_array('POL', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['POL1'] = self::$_per['POL']['POL1']; // 1 полугодие
								self::previosPOL('POL1', $y, $aResult);
							}
							break;
						}
					case 9:
						{
							if (!(isset($periodOptions['use']) && !(in_array('KV', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['KV3'] = self::$_per['KV']['KV3'];  // 3 кв.
								self::previosKV('KV3', $dt->format('Y'), $aResult);
							}
	
							if (!(isset($periodOptions['use']) && !(in_array('9MES', $periodOptions['use']))))
								$aResult[$dt->format('Y')]['9MES'] = self::$_per['9MES']['9MES']; // 9 месяцев
								break;
						}
					case 12:
						{
							if (!(isset($periodOptions['use']) && !(in_array('KV', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['KV4'] = self::$_per['KV']['KV4']; // 4 кв.
								self::previosKV('KV4', $dt->format('Y'), $aResult);
							}
	
							if (!(isset($periodOptions['use']) && !(in_array('POL', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['POL2'] = self::$_per['POL']['POL2']; // 2 полугодие
								self::previosPOL('POL2', $y, $aResult);
							}
	
							if (!(isset($periodOptions['use']) && !(in_array('GOD', $periodOptions['use']))))
							{
								$aResult[$dt->format('Y')]['GOD'] = self::$_per['GOD']['GOD']; // год
								self::previosGOD($y, $aResult);
							}
							break;
						}
				}
			}
	
			return $aResult;
	}*/
	
	
	private static function previosKV($currentKV, $currentYear, &$a)
	{		
		switch ($currentKV)
		{
			case 'KV1':
				{					
					if (isset($a[$currentYear-1]) && in_array('KV4', $a[$currentYear-1])) 
					{
						$a[$currentYear]['previos']['KV1'] = ['year'=>($currentYear-1), 'period'=>'KV4'];						
					}
					break;
				}
			case 'KV2':
				{					
					if (in_array('KV1', $a[$currentYear]))
					{
						$a[$currentYear]['previos']['KV2'] = ['year'=>($currentYear), 'period'=>'KV1'];
					}
					break;
				}
			case 'KV3':
				{					
					if (in_array('KV2', $a[$currentYear]))
					{
						$a[$currentYear]['previos']['KV3'] = ['year'=>($currentYear), 'period'=>'KV2'];
					}
					break;
				}
			case 'KV4':
				{					
					if (in_array('KV3', $a[$currentYear]))
					{
						$a[$currentYear]['previos']['KV4'] = ['year'=>($currentYear), 'period'=>'KV3'];						
					}
					break;
				}
		}
	}
	
	
	private static function previosPOL($currentPOL, $currentYear, &$a)
	{
		if ($currentPOL == 'POL1')
		{
			if (isset($a[$currentYear-1]) && in_array('POL2', $a[$currentYear-1]))
			{
				$a[$currentYear]['previos']['POL1'] = ['year'=>($currentYear-1), 'period'=>'POL2'];
			}
		}
		else 
		{
			if (in_array('POL1', $a[$currentYear]))
			{
				$a[$currentYear]['previos']['POL2'] = ['year'=>$currentYear, 'period'=>'POL1'];
			}
		}
	}
	
	private static function  previosGOD($currentYear, &$a)
	{
		if (isset($a[($currentYear-1)]))
		{
			$a[$currentYear]['previos']['GOD'] = ['year'=>($currentYear-1), 'period'=>'GOD'];
		}
	}
	
	/**
	 * Проверка корректности периодов
	 * @param array $period
	 * @return boolean
	 */
	private static function checkPeriod($period)
	{
		// isset
		if (!isset($period['beginMes']) || !isset($period['endMes']))
			return false;
		if (!isset($period['beginYear']) || !isset($period['endYear']))
			return false;
	
		// numeric
		if (!is_numeric($period['beginMes']) || !is_numeric($period['endMes']))
			return false;
		if (!is_numeric($period['beginYear']) || !is_numeric($period['endYear']))
			return false;
							
		return true;
	}
	
	
	/**
	 * @return string[]
	 */
	public static function months()
	{
		return [
			'01' => 'январь',
			'02' => 'февраль',
			'03' => 'март',
			'04' => 'апрель',
			'05' => 'май',
			'06' => 'июнь',
			'07' => 'июль',
			'08' => 'август',
			'09' => 'сентябрь',
			'10' => 'октябрь',
			'11' => 'ноябрь',
			'12' => 'декабрь',
		];
	}
	
	
	public static function periods()
	{
		return self::$_attr;
	}
	
	public static function periodValueByKey($key)
	{
		return (isset(self::$_attr[$key]) ? self::$_attr[$key] : null);
	}
	
	
	public static function forSQL($periods, $tableAlias)
	{		
		$result = '';
		
		foreach ($periods as $period)
		{
			if ($result !== '')
			{
				$result .= ' or ';
			}
			
			$result .= "([{$tableAlias}].[period_year] = '{$period['year']}' and [{$tableAlias}].[period] = '{$period['period']}')";
		}
						
		return $result;
	}

	
	
}