<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Success registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regged">

    <span>Success registration. Please <?=Html::a('login',['/site/login']); ?>. </span>

</div>
