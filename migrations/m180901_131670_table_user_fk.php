<?php

use yii\db\Migration;

/**
 * Class m180901_131670_table_user_fk
 */
class m180901_131670_table_user_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
                'fk-user-user_client_id', 
                '{{%user}}', 
                'user_client_id', 
                '{{%clients}}', 
                'clients_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-user-user_rent_id', 
                '{{%user}}', 
                'user_rent_id', 
                '{{%rents}}', 
                'rents_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180901_131670_table_user_fk cannot be reverted.\n";

        return false;
    }

}
