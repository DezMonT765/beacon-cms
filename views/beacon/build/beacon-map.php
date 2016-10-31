<head><link href="style.b810d9f544bdbe34c905.css" rel="stylesheet"></head><?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 29.04.2015
 * Time: 10:38
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

\app\assets\Select2Asset::register($this);
$file_models = $map_provider->getModels();
$file_name = null;
$file_id = null;
foreach($file_models as $id => $name) :
    $file_name = $name;
    $file_id = $id;

endforeach
?>

<br>
<div id="root">

</div>

<script type="text/javascript" src="manifest.13543376d0b33bf42781.js"></script><script type="text/javascript" src="vendor.a04bd8d2f012d09cfa79.js"></script><script type="text/javascript" src="style.b810d9f544bdbe34c905.js"></script><script type="text/javascript" src="app.1d6c83268838839a6915.js"></script>