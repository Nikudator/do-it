<?php

namespace app\modules\user\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $agreement = false;
    public $verifyCode;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Это имя пользователя уже существует.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Такой email уже существует.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['agreement', 'required'],
            ['agreement', 'boolean'],
            ['agreement', 'compare', 'compareValue' => 1, 'message' => 'Для регистрации на сайте вы должны принять Пользовательское соглашение (Правила).'],


            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();
            $user->role = Yii::$app->params['defaultRole'];

            if ($user->save()) {
                Yii::$app->mailer->compose('@app/modules/user/mail/emailConfirm', ['user' => $user])
                    ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Подтверждение адреса электронной почты для сайта ' . Yii::$app->name)
                    ->send();
                return $user;
            }
        }

        return null;
    }
}