<?php

    use yii\db\Migration;

/**
 * Регистрация участния в голосовании
 */
class m181023_074847_table_registration_in_voting extends Migration
{
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%registration_in_voting}}', [
            'id' => $this->primaryKey(),
            'voting_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'random_number' => $this->integer(5)->notNull(),
            'date_registration' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(false),
        ]);
        
        $this->createIndex('idx-registration_in_voting-id', '{{%registration_in_voting}}', 'id');
        $this->createIndex('idx-registration_in_voting-voting_id', '{{%registration_in_voting}}', 'voting_id');
        
        $this->addForeignKey(
                'fk-registration_in_voting-voting_id', 
                '{{%registration_in_voting}}', 
                'voting_id', 
                '{{%voting}}', 
                'voting_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    public function safeDown() {
        
        $this->dropIndex('idx-registration_in_voting-id', '{{%registration_in_voting}}');
        $this->dropForeignKey('fk-registration_in_voting-voting_id', '{{%registration_in_voting}}');
        $this->dropTable('{{%registration_in_voting}}');
    }

}
