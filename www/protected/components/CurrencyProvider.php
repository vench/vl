<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Класс отвечает за поставку валют.
 *
 * @author v.raskin
 */
class CurrencyProvider {
    /**
     *
     * @var CurrencyProvider 
     */
    private static $inst = NULL;

    /**
     *  
     */
    private $data = NULL;
    
    /**
     *
     * @var type 
     */
    private $dateTime = NULL;
    
    private function __construct() { }
    
    /**
     *
     * @return CurrencyProvider 
     */
    public static function getInstance() {
        if(is_null(self::$inst)) {
            self::$inst = new CurrencyProvider();
        }
        return self::$inst;
    }
    
    /**
     * Получить дату последнего обновления.
     * @return int
     */
    public function getDateTime() {
        if(is_null($this->dateTime)) {
            $this->loadValuteies();
        }
        return $this->dateTime;
    }
    
    /**
     * Получить полный список валют.
     * @return array 
     */
    public function getValuteies() {
        if(is_null($this->data)) {
            $this->loadValuteies();
        }
        return $this->data;
    }
    
    /**
     * Получить валюту по буквенному коду. 
     * @return array 
     */
    public function getValuteByCharCode($charCode) {
        $values = $this->getValuteies();
        foreach($values as $value) {
            if($value['CharCode'] == $charCode){
                return $value;
            }
        }
        return NULL;
    }
    
    /**
     * Загружает данные о валютах
     */
    public function loadValuteies() {
        $url =  'http://www.cbr.ru/scripts/XML_daily.asp';  
        $data = simplexml_load_file($url);
        $this->data = array();
        foreach($data->Valute as $item) {  
            $this->data[] = array(
                'NumCode'=>(string)$item->NumCode,
                'CharCode'=>(string)$item->CharCode,
                'Nominal'=>(string)$item->Nominal,
                'Name'=>(string)$item->Name,
                'Value'=>str_replace(',','.',(string)($item->Value)), 
            );  
        }
  
      
        $this->dateTime = strtotime((string)$data['Date']);
        
    }
}

?>
