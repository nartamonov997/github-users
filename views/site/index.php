<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Репозитории</h1>

        <p class="lead">Информация о репозиториях заданных пользователей обновляется раз в 10 минут. <br/>Задать список пользователей можно по кнопке ниже.</p>

        <p><a class="btn btn-lg btn-success" href="<?=Url::toRoute(['github-user/index']);?>">Изменить список пользователей</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
              <?php if ($githubRepositories) { ?>
              <table class="repository_list">
                <tr>
                    <th>Репозиторий</th>
                    <th>Дата обновления(МСК)</th>
                </tr>
                <?php foreach ($githubRepositories as $githubRepository) { ?>
                <tr>
                  <td><a href="<?=$githubRepository->url?>" target="_blank"><?=$githubRepository->full_name?></a></td>
                  <td><?=$githubRepository->updated?></td>
                </tr>  
                <?php } ?>
              </table>  
              <?php } else { ?>
                <p>Репозитории отсутствуют.</p>
              <?php } ?>
                <p>Дата, когда мы последний раз обновляли информацию по репозиториям(МСК): <?=($dateUpdateUserRepositories) ? $dateUpdateUserRepositories : 'отсутствует'?></p>
            </div>  
        </div>

    </div>
</div>
