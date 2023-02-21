<?php

namespace app\modules\user\models;

use yii\base\Model;
use Yii;

/**
 * Password reset request form
 */
class PasswordRequestForm extends Model
{
    public $email;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'app\modules\user\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с таким email не найден.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose('@app/modules/user/mail/passwordReset', ['user' => $user])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Восстановление пароля от сайта ' . Yii::$app->name)
            ->send();
    }
}