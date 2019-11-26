<?php


class m191122_234800_create_apple_table extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->createTable('{{%apple}}',[
            'id' => $this->primaryKey(),
            'color' => $this->string(18),
            'size' => $this->float()->defaultValue(1),
            'top' => $this->string(),
            'left' => $this->string(),
            'status' => $this->integer(1)->defaultValue(0),
            'fall_at' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}