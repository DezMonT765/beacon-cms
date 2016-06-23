<?php

use yii\db\Migration;

class m160623_155453_alter_beacon_pins extends Migration
{


    public function safeUp()
    {
        $this->dropForeignKey('beacon_pins_to_groups','beacon_pins');
        $this->renameColumn('beacon_pins','group_id','group_file_id');
        $this->addForeignKey('beacon_pins_to_group_files','beacon_pins','group_file_id','group_files','id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('beacon_pins_to_group_files','beacon_pins');
        $this->renameColumn('beacon_pins','group_file_id','group_id');
        $this->addForeignKey('beacon_pins_to_groups','beacon_pins','group_id','groups','id','CASCADE','CASCADE');
    }

}
