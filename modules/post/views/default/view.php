<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\markdown\Markdown;
use app\modules\comments\widgets\CommentsWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\post\models\Post */

$this->title = $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->name . ': ' . Html::encode($this->title)]);
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (\Yii::$app->user->can('update') || \Yii::$app->user->can('updateOwnPost', ['author_id' => $model->author_id])) {
            echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'],);
        }

        ?>
        <?php
        if (\Yii::$app->user->can('delete') || \Yii::$app->user->can('deleteOwnPost', ['author_id' => $model->author_id])) {
            echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы точно хотите удалить статью '.Html::encode($this->title),
                    'method' => 'post',
                ],
            ],);
        }
        ?>
    </p>
    <div class="tags">
        Тэги: <?php foreach($model->getTagPost()->all() as $post) : ?>
            <?= $post->getTag()->one()->title ?>
        <?php endforeach; ?>
    </div>

    <div class="blog-anons"><?php echo Html::encode($model->anons);?></div>
    <p></p>

    <?= empty(trim(Html::encode($model->youtube))) ? false : '<div id="ytplayer"></div>' ;?>

    <script>
        // Load the IFrame Player API code asynchronously.
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/player_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Replace the 'ytplayer' element with an <iframe> and
        // YouTube player after the API code downloads.
        var player;
        function onYouTubePlayerAPIReady() {
            player = new YT.Player('ytplayer', {
                height: '360',
                width: '640',
                videoId: '<?php echo Html::encode($model->youtube);?>'
            });
        }
    </script>
    <p></p>
    <div class="blog-body"><?php echo Markdown::convert($model->body); ?></div>

    <div>
        <span class="pull-left text-capitalize">Автор: <?= $model->author->username; ?>  Опубликовано: <?= $model->getRelTimeDate(); ?> <?= $model->getDate() === $model->getUpdate() ? false : 'Обновлено: ' . $model->getRelTimeUpdate(); ?></span>
    </div>
</div>

<?php echo CommentsWidget::widget([
    'master_id' => $model->id,
]); ?>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
<script>hljs.highlightAll();</script>