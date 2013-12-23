<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Набор утилит.
 *
 * @author v.raskin
 */
class Utilites {
	//put your code here
	
	private function __construct() { }
	
	/**
	 * Позволяет преобразовать колекцию CActiveRecord в простой масси.
	 * @param /CActiveRecord $objectsAR коллекция классов CActiveRecord.
	 * @param array $fields поля которые нужно положить в масси.
	 * @return array простой массив с полями из коллекции.
	 */
	public static function convertObjectARToSimpleArray($objectsAR, $fields) {
		$data = array();
		foreach($objectsAR as $ar) {
			$row = array();
			foreach($fields as $field){
				$row[$field] = $ar->{$field};
			}
			array_push($data, $row);
		}
		return $data;
	}
	
	/**
	 *
	 * @param integer $price цена
	 * @param string $format формат цены
	 * @return string 
	 */
	public static function priceFormat($price, $format = NULL) {
		if(is_null($format)) {
			$format = Yii::app()->params['priceFormat'];
		}
		return Yii::app()->numberFormatter->format($format, $price).' p.';
	}
}

?>
