<?php

use yii\db\Migration;

class m161106_144555_group_dynamic_maps extends Migration
{
    public $table = 'beacon_maps';

    public function safeUp()
    {
        $this->createTable($this->table,[
            'id' => $this->integer(),
            'data' => $this->text()
        ]);
        $this->addPrimaryKey('maps_key',$this->table,'id');
        $this->addForeignKey('map_to_group_file',$this->table,'id','group_files','id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('map_to_group_file',$this->table);
        $this->dropTable($this->table);
    }

}
