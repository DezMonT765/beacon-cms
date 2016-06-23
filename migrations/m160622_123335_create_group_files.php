<?php

use yii\db\Migration;

/**
 * Handles the creation for table `group_files`.
 */
class m160622_123335_create_group_files extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('group_files', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer(),
            'name' => $this->string(255),
            'type' => $this->string(255)
        ],$table_options);
        
        $this->addForeignKey('group_files_to_group','group_files','owner_id','groups','id','CASCADE','CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('group_files_to_group','group_files');
        $this->dropTable('group_files');
    }
}
