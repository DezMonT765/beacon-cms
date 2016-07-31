<?php

use yii\db\Migration;

class m160731_094907_beacon_content_element extends Migration
{

    public function safeUp()
    {
        $this->createTable('beacon_content_elements',[
           'id' => $this->primaryKey(),
           'beacon_id' => $this->integer(),
           'title' => $this->string(255),
           'link' => $this->string(512),
           'description' => $this->text(),
           'picture' => $this->string(255),
           'horizontal_picture' => $this->string(255),
           'additional_info' =>$this->text(),
        ]);
        $this->addForeignKey('beacon_content_elements_to_beacon','beacon_content_elements','beacon_id','beacons','id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('beacon_content_elements_to_beacon','beacon_content_elements');
        $this->dropTable('beacon_content_elements');
    }

}
