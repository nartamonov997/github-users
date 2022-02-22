<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\GitHubUser */

$this->title = 'Добавить пользователя Github';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи Github', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="git-hub-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
