<?php

namespace app\modules\user\controllers;

use app\modules\user\models\EmailConfirmForm;
use app\modules\user\models\LoginForm;
use app\modules\user\models\ResendVerificationEmailForm;
use app\modules\user\models\PasswordResetForm;
use app\modules\user\models\PasswordRequestForm;
use app\modules\user\models\SignupForm;
use app\modules\user\models\Auth;
use app\modules\user\models\User;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!\Yii::$app->user->can($action->id)) {
                throw new ForbiddenHttpException('Отказано в доступе. Не достаточно прав.');
            }
            return true;
        } else {
            return false;
        }
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        try {
            $model = new SignupForm();
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Подтвердите ваш электронный адрес. Проверьте указанный Вами ящик электронной почты. Так же на всякий случай проверьте папку "Спам"');
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionEmailConfirm($token)
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш Email успешно подтверждён.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения Email.');
        }

        return $this->goHome();
    }

    public function actionPasswordRequest()
    {
        try {
            $model = new PasswordRequestForm();
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Спасибо! На ваш Email было отправлено письмо со ссылкой на восстановление пароля.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Извините. У нас возникли проблемы с отправкой.');
            }
        }

        return $this->render('passwordRequest', [
            'model' => $model,
        ]);
    }

    public function actionPasswordReset($token)
    {
        try {
            $model = new PasswordResetForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');

            return $this->goHome();
        }

        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою почту и следуйте присланным инструкциям.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Извините, произошла ошибка и мы не можем отправить письмо с инструкциями на указанный email.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionProfile()
    {

    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::getClientId_AtributesId($client->getId(), (isset($attributes['id']))?$attributes['id']:$attributes['uid']);

         switch ($client->getId())
        {
            case 'vkontakte':
                $username = $attributes['screen_name'];
                break;
            case 'yandex':
                $username = $attributes['first_name'].' '.$attributes['last_name'];
                break;
            case 'odnoklassniki':
                $username = $attributes['first_name'].' '.$attributes['last_name'];
                $attributes['id'] = $attributes['uid'];
                break;
        }

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = User::findOne(['id' => $auth->user_id]);
                Yii::$app->user->login($user);
                Yii::$app->session->setFlash('success', 'Вы успешно вошли с помощью учетной записи '.$client->getId().'.');
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $email = isset($attributes['email']) ? $attributes['email'] : $attributes['id'].'@one-eyed.ru';
                    $user = new User([
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'status' => User::STATUS_ACTIVE,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],

                        ]);
                        if ($auth->save()) {
                            Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались с помощью учетной записи '.$client->getId().'.');
                            $transaction->commit();
                            Yii::$app->user->login($user);
                            Yii::$app->session->setFlash('success', 'Вы успешно вошли с помощью учетной записи '.$client->getId().'.');
                        } else {
                            print_r($auth->getErrors());
                            Yii::$app->session->setFlash('error', 'Не удалось войти с помощью учетной записи '.$client->getId().'. <br> Ошибка: '.$auth->getErrors());
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Не удалось зарегистрироваться с помощью учетной записи '.$client->getId().'. <br> Ошибка: '.$user->getErrors());
                    }
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
                Yii::$app->session->setFlash('success', 'Вы успешно вошли с помощью учетной записи '.$client->getId().'.');
            }
        }
    }

}