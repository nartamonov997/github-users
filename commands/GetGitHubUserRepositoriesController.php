<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\GithubUser;
use app\models\GithubRepository;
use yii\helpers\Json;

/**
 * Получить репозитории пользоваталей
 */
class GetGithubUserRepositoriesController extends Controller
{
    // количество репозиториев, которые по итогу надо будет вывести
    private static $countRepositories = 10;
    
    private static $curl;
    private static function getCurl() {
        if (!self::$curl) {
            self::$curl = curl_init();
        }
        
        return self::$curl;
    }
    private static function curlClose() {
        if (self::$curl) {
            self::$curl = curl_close(self::$curl);
        }
        return;
    }
    
    public function actionIndex()
    {
        // Получить репозитории пользователей
        $repositories = self::getRepositories();
        // Записать эти репозитории пользователей в базу данных
        self::writeRepositoriesToDb($repositories);
        self::curlClose();

        return ExitCode::OK;
    }
    
    /**
    * Получить репозитории пользователей
    */
    public static function getRepositories(): array
    {
        $githubUsers = GithubUser::find()->all();
        
        // репозитории всех пользоваталей
        $repositories = [];
        foreach ($githubUsers as $githubUser) {
            $userRepositories = self::getRepositoriesForUser($githubUser);
            $repositories = array_merge($repositories, $userRepositories);
        }
        
        // сортируем репозитории по дате обновления по убыванию
        usort($repositories, function ($a, $b) {
            if ($a['updated_timestamp'] == $b['updated_timestamp']) {
                return 0;
            }
            return ($a['updated_timestamp'] > $b['updated_timestamp']) ? -1 : 1;
        });
        
        $repositories = array_slice($repositories, 0, self::$countRepositories);
        
        return $repositories;
    }
    
    /**
    * Получить репозитории конкретного пользователя
    * @param GithubUser $githubUser
    */
    public static function getRepositoriesForUser($githubUser): array
    {
        $curl = self::getCurl();
        $githubUserName = $githubUser->name;
        $countRepositories = self::$countRepositories;
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.github.com/users/{$githubUserName}/repos?sort=updated&per_page={$countRepositories}",
            CURLOPT_USERAGENT => 'nartamonov997',
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $json = curl_exec($curl);
        $curlhttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($curlhttpCode != 200) {
            \Yii::warning('Curl код ответа с ошибкой, $curlhttpCode: ' . $curlhttpCode, __METHOD__);
            return [];
        }

        $repositories = Json::decode($json);
        $userRepositories = [];
        foreach ($repositories as $repository) {
            $date = new \DateTime($repository['updated_at']);
            $userRepositories[] = [
                'github_user_id'    => $githubUser->id,
                'url'               => $repository['html_url'],
                'full_name'         => $repository['full_name'],
                'updated_timestamp' => $date->getTimestamp(),
            ];
        }

        return $userRepositories;
    }
    
    /**
    * Записать репозитории в базу данных
    * @param array $repositories
    */
    public static function writeRepositoriesToDb($repositories)
    {
        GithubRepository::getDb()->transaction(function($db) use ($repositories) {
            // удалим старые записи
            GithubRepository::deleteAll();
            
            // запишем новые записи
            foreach ($repositories as $repository) {
                $repoDb = new GithubRepository();
                $repoDb->url = $repository['url'];
                $repoDb->full_name = $repository['full_name'];
                $repoDb->github_user_id = $repository['github_user_id'];
                $repoDb->updated = date('Y-m-d H:i:s', $repository['updated_timestamp']);
                if (!$repoDb->save()) {
                    $errors = $repoDb->getErrors();
                    \Yii::warning('Не удалось сохранить репозиторий, errors: ' . json_encode($errors), __METHOD__);
                }
            }
        });
    }
}
