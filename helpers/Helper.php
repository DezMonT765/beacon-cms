<?php
namespace app\helpers;
class Helper {
    /**
     * Recursively implodes an array with optional key inclusion
     *
     * Example of $include_keys output: key, value, key, value, key, value
     *
     * @access  public
     * @param   array   $array         multi-dimensional array to recursively implode
     * @param   string  $glue          value that glues elements together
     * @param   bool    $include_keys  include keys before their values
     * @param   bool    $trim_all      trim ALL whitespace from string
     * @return  string  imploded array
     */
    public static function  recursive_implode(array $array, $glue = ',', $include_keys = false, $trim_all = true)
    {
        $glued_string = '';
        // Recursively iterates array and adds key/value to glued string
        array_walk_recursive($array, function($value, $key) use ($glue, $include_keys, &$glued_string)
        {
            $include_keys and $glued_string .= $key.$glue;
            $glued_string .= $value.$glue;
        });
        // Removes last $glue from string
        strlen($glue) > 0 and $glued_string = substr($glued_string, 0, -strlen($glue));
        // Trim ALL whitespace
        $trim_all and $glued_string = preg_replace("/(\s)/ixsm", '', $glued_string);
        return (string) $glued_string;
    }


    public static function _is_link($target) {
        if(is_link($target))
            return true;

        $realpath = realpath($target);
        if ($realpath && $realpath !== $target)
            return true;

        return false;
    }

    public static function getRandomStubImage(){
        $images = "";
        return $images;
    }

    public static function getRandomItemFromArray(&$array,$output_count,$fill_empty = false)
    {
        $user_array = array();
        $rand_count = count($array) >= $output_count ? $output_count : count($array);
        for($i = 0; $i<$rand_count; $i++)
        {
            shuffle($array);
            $user_array[] = $array[0];
            unset($array[0]);
        }
        if($fill_empty) {
            for($i= $rand_count; $i< $output_count; $i++) {
                $user_array[] = null;
            }
        }
        return $user_array;
    }
}