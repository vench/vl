<?php

/**
 * 
 */
class SiteController extends BaseController
{ 
	/**
	 * 
	 */
	public function actionIndex() { 
            
	    $this->render('index', array( 
                'appName'=>Yii::app()->name,
            ));
            
	}
        
       
        /**
         * 
         */
	public function actionError() {
	    if(($error = Yii::app()->errorHandler->error)) {
		if(Yii::app()->request->isAjaxRequest) {
		    echo CJSON::encode(array('error'=>$error));
                } else {
                    $this->render('error', $error);
                }			
	    }
	} 
}
?>