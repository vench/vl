<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(
    'sourceLanguage' => 'ru',
    'language' => 'ru',
	'charset' => 'utf-8',
	
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'homeUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/',
	
    'name' => 'Курс валюты',
	
    'theme'=>'default',

    'import' => array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
    ), 
    'components' => array(
	    'errorHandler' => array(
			'errorAction' => 'site/error',
	     ),		
	      
            'db'=>array( 
			'connectionString' => 'mysql:host=localhost;dbname=vilmo',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'vl_',
		), 
		
     ),	
    'params' => array(
		'postsPerPage' => 10,
		'phpDateFormat' => 'd.m.Y',
		'jsDateFormat' =>' dd.mm.yyyy',
		'dirUploads' => 'uploads',
		'priceFormat'=>'#,##0.00', 
    ),
); 

?>