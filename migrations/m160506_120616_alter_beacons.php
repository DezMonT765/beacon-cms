<?php

use yii\db\Schema;
use yii\db\Migration;

class m160506_120616_alter_beacons extends Migration
{

    public function safeUp()
    {
        $this->addColumn('beacons','horizontal_picture',$this->string(255));
    }

    public function safeDown()
    {
        $this->dropColumn('beacons','horizontal_picture');
    }

}
