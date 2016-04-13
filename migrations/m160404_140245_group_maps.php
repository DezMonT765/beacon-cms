<?php

use yii\db\Schema;
use yii\db\Migration;

class m160404_140245_group_maps extends Migration
{

    public function safeUp()
    {
        $this->addColumn('groups','map',$this->string());
        $this->addColumn('beacon_pins','group_id',$this->integer());
        $this->addForeignKey('beacon_pins_to_groups','beacon_pins','group_id','groups','id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        $this->dropColumn('groups','map');
        $this->dropForeignKey('beacon_pins_to_groups','beacon_pins');
        $this->dropColumn('beacon_pins','group_id');
    }

}
