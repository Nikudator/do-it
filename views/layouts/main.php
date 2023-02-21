<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\icons\Icon;
Icon::map($this);

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php Yii::$app->user->isGuest ? Yii::$app->session->setFlash('warning', "Наш сайт использует файлы cookes, если вы не согласны с этим, то покиньте сайт.") : false; ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->name . ' ' . Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo.png', ['alt'=>Yii::$app->name, 'title'=>Yii::$app->name, 'class'=>"img-responsive",], ),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => array_filter([
            Yii::$app->user->can('create') ?
                ['label' => Icon::show('feather-alt', ['class'=>'fa-lg']), 'url' => ['/post/create'], 'options' => ['title' => 'Создать запись']] : false,
            ['label' => Icon::show('envelope', ['class'=>'fa-lg']).' Контакты', 'url' => ['/site/contact'], 'options' => ['title' => 'Контакты']],
            ['label' => Icon::show('info-circle', ['class'=>'fa-lg']).' Обо мне', 'url' => ['/site/about'], 'options' => ['title' => 'Обо мне']],
            ['label' => Icon::show('scroll', ['class'=>'fa-lg']).' Правила', 'url' => ['/site/agreement'], 'options' => ['title' => 'Правила']],
            Yii::$app->user->isGuest ?
            ['label' => Icon::show('user-plus', ['class'=>'fa-lg']).' Регистрация', 'url' => ['/user/default/signup'], 'options' => ['title' =>'Зарегистрироваться']] : false,
            //Yii::$app->user->isGuest ?
            //['label' => Icon::show('key', ['class'=>'fa-lg']), 'url' => ['/password-request'].' Контакты', 'options' => ['title' =>'Восстановить пароль']] : false,
            Yii::$app->user->isGuest ?
            (['label' => Icon::show('door-open', ['class'=>'fa-lg']).' Войти', 'url' => ['/login'], 'options' => ['title' => 'Войти на сайт']]
            ) : (


                '<li>'
                . Html::beginForm(['/logout'], 'post')
                . Html::submitButton(
                    Icon::show('door-closed', ['class'=>'fa-lg']).' Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout', 'title' =>'Выйти']
                )
                . Html::endForm()
                . '</li>'
            )
        ])
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="content col-sm-9"><?= $content ?></div>
        <div class="right-panel content col-sm-3"></div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Одноглазый Джо aka One-eyed Joe 2019 - <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?> / <a href="http://php.net">PHP</a> / <a href="http://apache.org">Apache</a>
            / <a href="http://nginx.org">Nginx</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
