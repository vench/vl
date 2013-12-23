<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Модель AR валюты.
 *
 * @author v.raskin
 */
class Currency extends CActiveRecord {
    /**
     * Актуальность курса валют (сутки). 
     */
    const TIME_ACTUAL = 86400;
    
    /**
     *
     * @param string $className
     * @return type 
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Получаем список валют с актуальными значениями.
     * @param boolean $update Принудительно обновляет курс независимо от дат 
     * @return type 
     */
    public static function getCurrencyActualList($update = false) {
        $models = self::model()->findAll(array(
            'condition'=>'Enable=1',
        )); 
        
        $timeActual = time() - self::TIME_ACTUAL;
        foreach($models as $model) {
            if($update || $model->DateTimeUpdate < $timeActual) {
                $model->updateValue();
            }
        }
        return $models;
    }
    
    
    
    /**
     * Получить список всех валют.
     * @param boolean $load если true, то дает возможность загружать новые валюты, если других валют нет.  
     * @return type 
     */
    public static function getCurrencyList($load = false) {
        $models = self::model()->findAll(array(
            'condition'=>'Enable=0',
        ));
        if(sizeof($models) == 0 && $load) {
            $currencyProvider = CurrencyProvider::getInstance();
            $data = $currencyProvider->getValuteies();
            foreach($data as $item){
                $model = new Currency();
                foreach($item as $key=>$value){
                    $model->{$key} = $value;
                }
                $model->DateTimeUpdate = $currencyProvider->getDateTime();
                $model->Enable = 0;
                
                if($model->save()) {
                     array_push($models, $model);
                } 
            }
        }
        return $models;
    }
    
    /**
     * Обновляем валюту из удаленного источника.
     */
    public function updateValue() {
        $currencyProvider = CurrencyProvider::getInstance();
        $data = $currencyProvider->getValuteByCharCode($this->CharCode);
        if(isset($data['Value'])) {
            $this->Value = $data['Value'];
            $this->DateTimeUpdate = $currencyProvider->getDateTime();
            $this->save();
        }
    }
    
    /**
     *
     * @return type 
     */
    public function rules() {
        return array(
            array('NumCode,CharCode,Nominal,Name,Value,DateTimeUpdate,Enable', 'safe'),
        );
    }
    
    /**
     *
     * @return string 
     */
    public function tableName() {
        return '{{Currency}}';
    }
}

?>
