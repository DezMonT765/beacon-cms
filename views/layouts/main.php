<?php
use app\components\Alert;
use app\filters\SiteLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

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
        <?= Breadcrumbs::widget([
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                ]) ?>
        <?= $content ?>
    </div>
</div>
<?php $this->endContent()?>

