<?php

use yii\db\Migration;

/**
 * Class m200407_161823_create_post
 */
class m200407_161823_create_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post',[
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'descxription' => $this->text(),
            'user_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200407_161823_create_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200407_161823_create_post cannot be reverted.\n";

        return false;
    }
    */
}
