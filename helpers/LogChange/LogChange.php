<?php
	
namespace app\helpers\LogChange;



/**
 * История операций пользователя (аудит ползователя / журанл изменений)
 *  
 *  
 * Для использования в таблице должен быть создан стролбец log_change (тип данных: text),
 * в который сохраняется информация о выполненных операциях пользователем (например, создание, измнение...)
 * 
 * Перечень операций опеределены константами, начинающимися с OPERATION_
 * 
 * 
 * 1. Для сохранения в БД запись нужно преобразуть в строку 'datetime|operation|user_id|user_name'
 * 	 Например:
 *   	'01.03.2016 10:00:00|создание|1|admin$02.03.2016 12:00:00|изменение|2|user1'
 * В качестве разделителя используется символ определенный константой DELIMITER_WORD (по-умолчанию "|")
 * 
 * Для разделения одного изменения от другого используется символ
 *   определенный константой DELIMITER_SECTION (по-умолчанию "$")
 * 
 * 
 * 2. При выводе на экран данные из ячейки log_change БД преобразуются в массив
 *   следующего вида: 
 *   [
 *   	[
 *   		'datetime',  -- дата и время операции
 *      	'operation', -- операция, определенная одной из констант начинающихся с OPERATION_
 *      	'user_id',   -- ИД пользователя (из таблицы {{%user}})
 *      	'user_name', -- логин пользователя
 *   	],
 *   	...,   	
 *   ]  
 * 
 * @author oleg <trusov@r86.nalog.ru>
 * @version 02.03.2016
 */
class LogChange 
{
		
	// обозначение операций
	const OPERATION_CREATE = 'создание';
    const OPERATION_EDIT = 'изменение';
    const OPERATION_DELETE = 'удаление';
    
    // разделители
    const DEMILITER_WORD = '|';
    const DELIMITER_SECTION = '$';
    
    // формат даты (при записи в БД)
    const DATE_FORMAT = 'd.m.Y H:i:s';
    
    // папка с представлениями
    const PATH = '@app/helpers/LogChange/views';
    
    
    /**
     * Преобразование строки аудита в двумерный массив 
     * 
     * @param string $str
     * @param integer $delimiter_section
     * @param integer $delimiter_word
     * @return array: 
     */
    private static function explodeStr($str, $delimiter_section, $delimiter_word)
    {
    	$explode_section = explode($delimiter_section, $str); 
    	
    	for ($i=0; $i < count($explode_section); $i++)
    	{
    		$explode_section[$i] = explode($delimiter_word, $explode_section[$i]);
    	}    	
    		
    	return $explode_section;   	   
    }
         
    /**
     * Преобразования строки аудита для ее печати
     * 
     * @param string $str: строка из БД
     * @param string $delimiter_word: разделитель между переменными (необязательно)
     * @param string $delimiter_section: разделитель между операциями (необязательно)
     * 
     * @return string: список изменений
     */
    public static function auditDecode($str,
    	$delimiter_word = self::DEMILITER_WORD, 
    	$delimiter_section = self::DELIMITER_SECTION)
    {    	    
    	
    	$logArray = self::explodeStr($str, $delimiter_section, $delimiter_word);
    	
   		return \yii::$app->view->renderFile(self::PATH . '/index.php', 
   			['logArray' => $logArray]);         
    }
    
    
    /**
     * Преобразования строки аудита для ее печати
     * 
     * @param string $str: строка из БД
     * @param string $delimiter_word: разделитель между переменными (необязательно)
     * @param string $delimiter_section: разделитель между операциями (необязательно)
     * 
     * @return array: массив журнала изменений
     */
    public static function auditDecodeArray($str, 
    	$delimiter_word = self::DEMILITER_WORD, 
    	$delimiter_section = self::DELIMITER_SECTION)
    {
        return self::explodeStr($str, $delimiter_section, $delimiter_word); 
    }     
    
    
    /**
     * Преобразование строки аудита для записи в БД
     * 
     * @param string $str: строка с существующими данными
     * @param string $operation: операция (должна быть определена одной из констант OPERATION_...)
     * @param integer $user_id: индентифиактор пользователя (необязательно)
     * @param string $user_name: логин пользователя (необязательно)
     * @param string $delimiter_word: разделитель между переменными (необязательно)
     * @param string $delimiter_section: разделитель между операциями (необязательно)
     * 
     * @return string
     */
    public static function auditCode($str, $operation, 
    	$user_id = null,
    	$user_name = null, 
    	$delimiter_word = self::DEMILITER_WORD, 
    	$delimiter_section = self::DELIMITER_SECTION)
    {
    	if ($user_id === null)
    		$user_id = isset(\Yii::$app->user->id) ? \Yii::$app->user->id : '0';
    	
    	if ($user_name === null) 
    		$user_name = isset(\Yii::$app->user->name) ? \Yii::$app->user->name : 'guest';
        
    	if ($str !== '') 
    		$str .= self::DELIMITER_SECTION;
    	
    	return $str . date(self::DATE_FORMAT) . self::DEMILITER_WORD . $operation 
    		. self::DEMILITER_WORD . $user_id . self::DEMILITER_WORD . $user_name;
    }
	
}