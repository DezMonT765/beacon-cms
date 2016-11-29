<?php

use yii\db\Migration;

class m161127_094656_alter_users extends Migration
{

    public function safeUp()
    {
        $this->addColumn('users','api_key',$this->string());
        $this->addColumn('users','group_ids',$this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('users','api_key');
        $this->dropColumn('users','group_ids');
    }
}
