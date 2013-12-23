<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="/themes/green/images/icon.jpg" type="images/x-icon"> 

 
	<title><?= CHtml::encode($this->pageTitle .' | ' . Yii::app()->name); ?></title>
     
        <?php Yii::app()->getClientScript()->registerScriptFile( Yii::app()->request->baseUrl.'/themes/default/js/ext-all.js'); ?>
        <?php Yii::app()->getClientScript()->registerScriptFile( Yii::app()->request->baseUrl.'/themes/default/js/ext-lang-ru.js'); ?>
        <?php Yii::app()->getClientScript()->registerCssFile( Yii::app()->request->baseUrl.'/themes/default/resources/css/ext-all.css'); ?>
	
</head>

<body class="">
    <?= $content;   ?>    
</body>
</html> 