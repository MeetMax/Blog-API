<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Praise */

$this->title = 'Create Praise';
$this->params['breadcrumbs'][] = ['label' => 'Praises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="praise-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
