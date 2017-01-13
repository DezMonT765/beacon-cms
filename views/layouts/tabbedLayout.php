<?php
use dezmont765\yii2bundle\components\Alert;
use app\filters\SiteLayout;
use app\filters\TabbedLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
                         'items' => $this->getLayoutData(SiteLayout::place_left_nav)]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-right'],
                         'items' => $this->getLayoutData(SiteLayout::place_right_nav)]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::printAlert(); ?>
        <div class="row">
            <?= Nav::widget(
                [
                    'options' => ['class' => 'nav-tabs'],
                    'items' => $this->getLayoutData(TabbedLayout::place_tabs)
                ]) ?>
        </div>
        <div class="row" style="margin-top: 10px">
            <?php $items = $this->getLayoutData(TabbedLayout::place_top_control_buttons);
            foreach($items as $button) :
                if(isset($button['active']) && $button['active'] === true) :
                    echo Html::a(ArrayHelper::getValue($button, 'label'), ArrayHelper::getValue($button, 'url'),
                                 ArrayHelper::getValue($button, 'options'));
                endif;
            endforeach; ?>
        </div>
        <div class="row" >
        <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endContent() ?>
