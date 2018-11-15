<?php

use yii\db\Migration;

/**
 * Class m180901_131671_table_house_fk
 */
class m180901_131671_table_house_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
                'fk-houses-houses_account_id', 
                '{{%houses}}', 
                'houses_account_id', 
                '{{%personal_account}}', 
                'account_id', 
                'CASCADE',
                'CASCADE'
        );        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180901_131671_table_house_fk cannot be reverted.\n";

        return false;
    }

}
