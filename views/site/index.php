<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';


yii\web\JqueryAsset::register($this);
\mimicreative\react\ReactAsset::register($this);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js'); 

$this->registerJsFile('/js/app.js', [
    'type'  => 'text/babel',
    'position' => \yii\web\View::POS_BEGIN,
]);

 
?> 

<div class="site-index">

     

    <div class="body-content">

         <div class="" id="draw"></div>

    </div>
</div>
