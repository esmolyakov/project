<?php

use yii\db\Migration;

/**
 * Промежуточная таблица Пользователи - Лицевой счет
 */
class m180901_105322_table_account_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%account_to_users}}', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer(),
            'user_id' => $this->integer(),
        ], $table_options);
        
        $this->addForeignKey(
                'fk-account_to_users-account_id', 
                '{{%account_to_users}}', 
                'account_id', 
                '{{%personal_account}}', 
                'account_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-account_to_users-user_id', 
                '{{%account_to_users}}', 
                'user_id', 
                '{{%user}}', 
                'user_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-account_to_users-account_id', '{{%account_to_users}}');
        $this->dropIndex('idx-account_to_users-user_id', '{{%account_to_users}}');
        $this->addForeignKey('fk-account_to_users-account_id', '{{%account_to_users}}');
        $this->addForeignKey('fk-account_to_users-user_id', '{{%account_to_users}}');
        $this->dropTable('{{%account_to_users}}');
    }

}
