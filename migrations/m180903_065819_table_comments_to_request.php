<?php

    use yii\db\Migration;

/**
 * Комментарии к заявкам
 */
class m180903_065819_table_comments_to_request extends Migration
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
        
        $this->createTable('{{%comments_to_request}}', [
            'comments_id' => $this->primaryKey(),
            'comments_request_id' => $this->integer()->notNull(),
            'comments_text' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'comments_user_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-comments_to_request-comments_request_id', '{{%comments_to_request}}', 'comments_request_id');
        
        $this->addForeignKey(
                'fk-comments_to_request-comments_request_id', 
                '{{%comments_to_request}}', 
                'comments_request_id', 
                '{{%requests}}', 
                'requests_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-comments_to_request-comments_user_id', 
                '{{%comments_to_request}}', 
                'comments_user_id', 
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
        $this->dropIndex('idx-comments_to_request-comments_request_id', '{{%comments_to_request}}');
        $this->dropForeignKey('fk-comments_to_request-comments_request_id', '{{%comments_to_request]}');
        $this->dropForeignKey('fk-comments_to_request-comments_user_id', '{{%comments_to_request]}');
        $this->dropTable('{{%comments_to_request}}');
    }

}
