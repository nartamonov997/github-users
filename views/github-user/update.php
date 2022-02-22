<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\GitHubUser */

$this->title = 'Изменение пользователя Github: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи Github', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="git-hub-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
