<?php

use yii\db\Migration;

/**
 * Handles the creation for table `beacon_tags`.
 */
class m161210_091142_create_beacon_tags extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('beacon_tags', [
            'id' => $this->primaryKey(),
            'beacon_id' => $this->integer(),
            'tag_id' => $this->integer()
        ]);
        $this->addForeignKey('fk_beacons','beacon_tags','beacon_id','beacons','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_tags','beacon_tags','tag_id','tags','id','CASCADE','CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_beacons','beacon_tags');
        $this->dropForeignKey('fk_tags','beacon_tags');
        $this->dropTable('beacon_tags');
    }
}
