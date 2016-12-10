<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tags`.
 */
class m161210_090435_create_tags extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('tags', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('tags');
    }
}
