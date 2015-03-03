<?php
use app\components\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
/* @var $this \app\components\MainView */

include_once('header.php');
?>
<?php $this->beginBody() ?>
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
                         'items' =>$this->getLayoutData('left_nav')]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-right'],
                         'items' =>$this->getLayoutData('right_nav')]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::printAlert($this);?>
        <?= Nav::widget(
            [
                'options' => ['class'=>'nav-tabs'],
                'items' =>$this->getLayoutData('tabs')
            ]) ?>
        <?= $content ?>
    </div>
</div>
<?php include_once('footer.php');?>

