<?php

namespace app\modules\post\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property string $title
 *
 * @property TagPost[] $tagPosts
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[TagPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagPosts()
    {
        return $this->hasMany(TagPost::className(), ['tag_id' => 'id']);
    }
}
