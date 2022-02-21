<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "github_repositories".
 *
 * @property int $id
 * @property string $url
 * @property string $full_name
 * @property int $github_user_id
 * @property string $updated Дата обновления репозитория
 *
 * @property GithubUsers $githubUser
 */
class GithubRepository extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'github_repositories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'full_name', 'github_user_id', 'updated'], 'required'],
            [['github_user_id'], 'integer'],
            [['updated'], 'safe'],
            [['url', 'full_name'], 'string', 'max' => 1000],
            [['github_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => GithubUser::className(), 'targetAttribute' => ['github_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'full_name' => 'Full Name',
            'github_user_id' => 'Github User ID',
            'updated' => 'Updated',
        ];
    }

    /**
     * Gets query for [[GithubUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGithubUser()
    {
        return $this->hasOne(GithubUsers::className(), ['id' => 'github_user_id']);
    }
    
}
