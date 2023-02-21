<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\SignupForm */

$this->title = 'Регистрация';
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->name . ': ' . Html::encode($this->title)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для регистрации заполните поля:</p>

    <div class="row">
        <div class="col-lg-7">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username')->label('Имя пользователя') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
            <?= $form->field($model, 'agreement')->checkbox()->label('Я принимаю <a href="/agreement">Пользовательское соглашением (Правила)</a> сайта') ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/user/default/captcha',
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ])->label('Введите код с картинки') ?>
            <div class="form-group">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
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