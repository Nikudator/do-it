<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 */
class Auth extends \yii\db\ActiveRecord
{
    public $user;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth';
    }

    public static function getClientId_AtributesId($clientId, $attributesId)
    {
        return static::findOne(['source' => $clientId, 'source_id' => $attributesId]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
}
