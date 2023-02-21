<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\LoginForm */

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->name . ': ' . Html::encode($this->title)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для входа заполните поля:</p>

    <div class="row">
        <div class="col-lg-7">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя') ?>

            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

            <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/user/default/captcha',
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ])->label('Введите код с картинки') ?>

            <div style="color:#999;margin:1em 0">
                Если вы забыли пароль, вы можете восстановить
                его <?= Html::a('Восстановить пароль', ['/password-request']) ?>.
                <br>
                Если вам не пришло письмо для подтверждения email, вы можете его запросить
                повторно. <?= Html::a('Подтвердить email', ['/resend-verification-email']) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <div class="form-group">
                <label class="control-label">Так же вы можете войти с помощью одной из социальных сетей:</label>
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['/user/default/auth'],
                    'popupMode' => false,
                ]) ?>
                <label class="control-label">Входя или регистрируясь с помощью социальных сетей вы подтверждаете, что согласны с <a href="/agreement">Пользовательским соглашением (Правилами)</a> сайта</label>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>