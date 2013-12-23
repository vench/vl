<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Основной класс приложения API JSON
 *
 * @author v.raskin
 */
class ApiController  extends BaseController {
     
    /**
     * layout
     * @var string 
     */        
    public $layout = null;
        
    /**
     * Получить список выбранных валют. 
     */
    public function actionCurrencyActualList() {
        $update = Yii::app()->request->getParam('update', false);
        $models = Currency::getCurrencyActualList($update); 
        $this->pushForGrid($models);
    }
    
    /**
     * Добавить валюту для просмотра.
     */
    public function actionCurrencyAdd() {
        $result = false;
        $data = Yii::app()->request->getParam('id', null);
        if(is_array($data) && sizeof($data) > 0) { 
           $result = Currency::model()->updateByPk($data, array('Enable'=>'1'));
        }
        $this->push(array('result'=>$result,));
    }
    
    /**
     * Убрать валюту из просмотра. 
     */
    public function actionCurrencyRemove() {
        $id = Yii::app()->request->getParam('id', null);
        $model = $this->loadModelByPk('Currency', $id);
        $model->Enable = 0;
        $result = $model->save();
        $this->push(array('result'=>$result,));
    }
    
    /**
     * Получить список валют (всех возможных). 
     */
    public function actionCurrencyList() {
        $models = Currency::getCurrencyList(true); 
        $this->pushForGrid($models);
    }
    
    /**
     * Вытолкнкть результат для таблицы.
     * @param type $data 
     */
    public function pushForGrid($data) {        
        $this->push(array(
            'items'=> $data,
            'total'=> sizeof($data),
        ));
    }
    
    /**
     * Вытолкнкть результат.
     * @param mixed $data 
     */
    public function push($data) {
        echo CJSON::encode($data);
        Yii::app()->end();
    }
}

?>
