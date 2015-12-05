<?php
namespace app\helpers;
use app\components\AcImage;
use InvalidArgumentException;

/**
 * Created by JetBrains PhpStorm.
 * User: DezMonT
 * Date: 18.08.14
 * Time: 14:19
 * To change this template use File | Settings | File Templates.
 */

class HelperImage
{
    public static function deleteImage($original_file)
    {
        if(file_exists($original_file) && !is_dir($original_file))
            unlink($original_file);
    }

    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public static function imgCrop($outfile,$infile,$x1,$y1,$w,$h,$bound)
    {
        $scale = self::getScaleByDesiredBound($infile,$bound);
        $img = AcImage::createImage($infile);
        $img->setRewrite(true);
        $img->crop((int)($x1 * $scale),(int)($y1 * $scale),(int)($w * $scale),(int)($h * $scale));
        $img->save($outfile);
    }

    public static function imgCropByScale($outfile,$infile,$x1,$y1,$w,$h,$scale)
    {
        if($w == 0 || $h == 0)
            return false;
        $img = AcImage::createImage($infile);
        $img->setRewrite(true);
        $img->crop((int)($x1 * $scale),(int)($y1 * $scale),(int)($w * $scale),(int)($h * $scale));
        $img->save($outfile);
    }

    public static function imgSetDimension($outfile,$infile,$w,$h)
    {
        $img = AcImage::createImage($infile);
        $img->setRewrite(true);
        $img->resize($w,$h);
        $img->save($outfile);
    }

    public static function getScaleByDesiredBound($infile,$bound)
    {
        $img = AcImage::createImage($infile);
        $new_size = self::getSizeByBound($infile,$bound);
        $scale = $img->getWidth() / $new_size->getWidth();
        return $scale;
    }

    public static function getSizeByBound($file,$bound)
    {
        $img = AcImage::createImage($file);
        if($img->getWidth() > $img->getHeight())
            $new_size = $img->getSize()->getByWidth($bound);
        else
            $new_size = $img->getSize()->getByHeight($bound);
        return $new_size;
    }

    public static function resizeByBound($infile, $outfile,$bound) {
        $image = AcImage::createImage($infile);
        $image->setRewrite(true);
        $image->hardResize($image->getSize()->getByWidthBound($bound));
        $image->save($outfile);
    }


}