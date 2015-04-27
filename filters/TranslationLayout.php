<?php
namespace app\filters;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 18:50
 * @method static import()
 */

class TranslationLayout extends TabbedLayout
{

    public static function getActiveMap()
    {
        return array_merge(parent::getActiveMap(),[
           'import' => [TranslationLayout::import()]
        ]);
    }

    public static function layout($active = [])
    {
        return parent::layout(array_merge($active,[SiteLayout::translations()]));
    }

    public static function getTabs($active = [])
    {
        $tabs = [
            ['label'=>Yii::t('translation_layout',':translations_list'),'url'=>Url::to(['translation/list'] + $_GET),'active'=>self::getActive($active,TabbedLayout::listing())],
            ['label'=>Yii::t('translation_layout',':translations_import'),'url'=>Url::to(['translation/import'] + $_GET),'active'=>self::getActive($active,TranslationLayout::import())],
        ];
        return $tabs;
    }
}