<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Praise */

$this->title = 'Update Praise: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Praises', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="praise-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
