<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Обо мне';
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->name . ': ' . Html::encode($this->title)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Приветствую на моем сайте. Я блогер. Здесь я буду рассказывать и показывать всякое, что взбредет в мою голову.
    <p>Возможно это будет кому то интересно.
    <p>
    <p>Мой <a href="https://www.youtube.com/channel/UC6k9vQrMLnZPslhC7kUFhqg">Youtube-канал.</a>
    <p>Мой <a href="https://rutube.ru/channel/24237743/">Rutube-канал.</a>
    <p>Задонатить <a href="https://boosty.to/oej">на Boosty.</a>
    <p>Задонатить <a href="https://www.donationalerts.com/r/one_eyed_joe">на DonationAlerts.</a>
</div>
