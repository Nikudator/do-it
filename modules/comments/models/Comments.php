<?php

namespace app\modules\comments\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int $author_id
 * @property string|null $body
 * @property int $created_at
 * @property int $updated_at
 * @property int $parent_comment
 * @property int $answered_comment
 *
 * @property User $author
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'created_at', 'updated_at', 'parent_comment', 'answered_comment'], 'required'],
            [['author_id', 'created_at', 'updated_at', 'parent_comment', 'answered_comment'], 'integer'],
            [['body'], 'string'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'body' => 'Текст',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'parent_comment' => 'Parent Comment',
            'answered_comment' => 'Answered Comment',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

}
