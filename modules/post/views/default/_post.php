<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
?>
<div class="post">

    <div class="post-content">
        <header class="entry-header text-center">

            <h2 class="entry-title"><a
                        href="<?= Url::toRoute(['/post/view', 'id' => $post->id]); ?>"><?= $post->title ?></a></h2>

        </header>
        <div class="tags">
            Тэги: <?php //foreach($model->getTagPost()->all() as $post) : ?>
            <?php //$post->getTag()->one()->title ?>
            <?php //endforeach; ?>
        </div>
        <div class="entry-content">
            <p><?php echo Html::encode($post->anons); ?>
            </p>
            <p></p>

            <?= empty(trim(Html::encode($post->youtube))) ? false : '<div class="video" id="ytplayer' . $post->id . '"></div>'; ?>

            <p></p>
            <div class="btn-continue-reading text-center text-uppercase">
                <a href="<?= Url::toRoute(['/post/view', 'id' => $post->id]); ?>" class="more-link">Читать далее</a>
            </div>
        </div>
        <div
        <span class="pull-left text-capitalize">Автор: <?= $post->author->username; ?> Опубликовано: <?= $post->getRelTimeDate(); ?> <?= $post->getDate() === $post->getUpdate() ? false : 'Обновлено: ' . $post->getRelTimeUpdate(); ?></span>
    </div>
</div>
</div>
