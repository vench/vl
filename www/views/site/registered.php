<?php

/* @var $this yii\web\View */ 
/* @var $model \app\models\RegisteredForm  */
 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = 'Registered';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    

     <div class="formRegistered">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'phone') ?>
    
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- _formRegistered -->
</div>
