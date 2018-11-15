<?php

    use yii\db\Migration;
    use yii\db\Expression;

/**
 * Голосование
 * Вопросы
 * Ответы
 */
class m181005_073055_table_voting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $table_option = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%voting}}', [
            'voting_id' => $this->primaryKey(),
            'voting_type' => $this->tinyInteger()->notNull(),
            'voting_title' => $this->string(255)->notNull(),
            'voting_text' => $this->text(1000)->notNull(),
            'voting_date_start' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'voting_date_end' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'voting_house' => $this->integer()->notNull(),
            'voting_porch' => $this->integer()->notNull(),
            'voting_image' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'voting_user_id' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-voting-voting_id', '{{%voting}}', 'voting_id');
        
        $this->createTable('{{%questions}}', [
            'questions_id' => $this->primaryKey(),
            'questions_voting_id' => $this->integer()->notNull(),
            'questions_text' => $this->text(1000)->notNull(),
            'questions_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        $this->createIndex('idx-questions-questions_id', '{{%questions}}', 'questions_id');
        
        $this->createTable('{{%answers}}', [
            'answers_id' => $this->primaryKey(),
            'answers_questions_id' => $this->integer()->notNull(),
            'answers_vote' => $this->tinyInteger()->notNull(),
            'answers_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        $this->createIndex('idx-answers-answers_id', '{{%answers}}', 'answers_id');
        
        /* $this->createTable('{{%voting_to_participant}}', [
            'id' => $this->primaryKey(),
            'voting_id' => $this->integer()->notNull(),
            'estate_id' => $this->integer()->notNull(),
            'house_id' => $this->integer()->notNull(),
            'porch' => $this->integer()->notNull(),
            'flat_id' => $this->integer()->null(),
        ]);
        $this->createIndex('idx-voting_to_participant-voting_id', '{{%voting_to_participant}}', 'voting_id');
         */
        
        $this->addForeignKey(
                'fk-questions-questions_voting_id', 
                '{{%questions}}', 
                'questions_voting_id', 
                '{{%voting}}', 
                'voting_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-answers-answers_questions_id', 
                '{{%answers}}', 
                'answers_questions_id', 
                '{{%questions}}', 
                'questions_id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-voting-voting_id', '{{%voting}}');
        $this->dropIndex('idx-questions-questions_id', '{{%questions}}');
        $this->dropIndex('idx-answers-answers_id', '{{%answers}}');
        
        $this->dropForeignKey('fk-questions-questions_voting_id', '{{%questions}}');
        $this->dropForeignKey('fk-answers-answers_questions_id', '{{%answers}}');

        $this->dropTable('{{%answers}}');
        $this->dropTable('{{%questions}}');
        $this->dropTable('{{%voting}}');
    }

 }
