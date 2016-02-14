<?php
use app\components\Alert;
use app\filters\SiteLayout;
use app\filters\TabbedLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
/* @var $this \app\components\MainView */

$this->beginContent('@app/views/layouts/base-layout.php'); ?>
<div class="wrap">
    <?php
    NavBar::begin([
                      'brandLabel' => 'Beacon-CMS',
                      'brandUrl' => Yii::$app->homeUrl,
                      'options' => [
                          'class' => 'navbar-default navbar-fixed-top',
                      ],
                  ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-left'],
                         'items' =>$this->getLayoutData(SiteLayout::place_left_nav)]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-right'],
                         'items' =>$this->getLayoutData(SiteLayout::place_right_nav)]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::printAlert($this);?>
        <?= Nav::widget(
            [
                'options' => ['class'=>'nav-tabs'],
                'items' =>$this->getLayoutData(TabbedLayout::place_tabs)
            ]) ?>
        <?= $content ?>
    </div>
</div>
<?php $this->endContent()?>
