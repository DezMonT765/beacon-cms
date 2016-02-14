<?php
use app\assets\LandingAsset;
use app\components\MainView;
use yii\helpers\Html;


/* @var $this MainView */

LandingAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body id="page-top">
    <?php $this->beginBody() ?>
        <?=$content?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>