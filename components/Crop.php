<?php
namespace app\components;
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 05.10.2015
 * Time: 19:41
 */
class Crop  {
    const X1 = 'x1';
    const Y1 = 'y1';
    const WIDTH = 'w';
    const HEIGHT = 'h';
    const SCALE = 'scale';
    public static function getAttribute($picture,$attribute) {


        if(isset($_POST['Crop'])) {
            $crop = $_POST['Crop'];
            if(isset($crop[$picture])) {
                $crop_model = $crop[$picture];
                if(isset($crop_model[$attribute]))
                    return $crop_model[$attribute];
            }
        }
        return null;
    }
}