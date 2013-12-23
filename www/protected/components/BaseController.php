<?php
 
/**
 * Базовый контроллер приложения.
 * Содержит всякие плюшки.
 *
 * @author v.raskin
 */
class BaseController extends CController
{
	/**
	 * layout
	 */
	public $layout='//layouts/main';
	
	/**
	 * Хлебные крошки
	 * @var array 
	 */
	public $breadcrumbs = NULL;
	
	/**
	 *
	 * @return array 
	 */		
        public function filters() {
            return array(
                'accessControl'
            );
        }
	
	/**
	 * Проверка выражения на правильность.
	 * @param boolean $expr выражение
	 * @param string $mgs сообщение об ошибке
	 * @param integer $code код ошибки
	 * @throws CHttpException 
	 */
	public function ensure($expr, $mgs, $code = 404) {
		if(!$expr) {
			throw new CHttpException($code, $mgs);
		}
	}
	
	/**
	 * Автоматически ищет в $_REQUEST данные для класса ($model) и пытается их записать.
	 * @param CActiveRecord $model
	 * @return boolean 
	 */
	public function validateAndSaveModel(CActiveRecord $model) {
		$className = get_class($model);
		  
		if(isset($_REQUEST[$className])) { 
			$model->setAttributes($_REQUEST[$className]);
			return ($model->validate() && $model->save()); 
		}
		return false; 
	}
	
 
	/**
	 * Загрузка модели по ИД с выводом сообщения об ошибке, если модели нету.
	 * @param string $modelName Название класса модели
	 * @param integer $id ИД модели
	 * @param string $condition
	 * @param array $params
	 * @return CActiveRecord 
	 */
	public function loadModelByPk($modelName, $id, $condition = '', $params = NULL) {
		$model = CActiveRecord::model($modelName)->findByPk($id, $condition, $params);
		$this->ensure(!is_null($model), "Невозможно загрузить объект {$modelName} ({$id})");
		return $model; 
	}
        
 
}

?>
