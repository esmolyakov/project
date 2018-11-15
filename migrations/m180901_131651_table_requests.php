<?php

    use yii\db\Migration;
    use app\models\StatusRequest;

/**
 * Class m180901_131651_table_requests
 */
class m180901_131651_table_requests extends Migration
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
        
        $this->createTable('{{%requests}}', [
            'requests_id' => $this->primaryKey(),
            'requests_ident' => $this->string(20)->notNull(),
            'requests_type_id' => $this->integer()->notNull(),
            'requests_comment' => $this->text(255)->notNull(),
            'requests_phone' => $this->string(70)->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(StatusRequest::STATUS_NEW),
            'is_accept' => $this->tinyInteger(),
            'requests_grade' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),            
            'requests_dispatcher_id' => $this->integer(),
            'requests_specialist_id' => $this->integer(),
            'requests_account_id' => $this->integer(),
            'requests_account_id' => $this->integer(),
            'date_closed' => $this->integer(),
        ], $table_options);
        $this->createIndex('idx-requests-requests_id', '{{%requests}}', 'requests_id');
        $this->createIndex('idx-requests-requests_ident', '{{%requests}}', 'requests_ident');
        
        $this->createTable('{{%type_requests}}', [
            'type_requests_id' => $this->primaryKey(),
            'type_requests_name' => $this->string(100)->notNull(),
        ], $table_options);        
        
        $this->addForeignKey(
                'fk-requests-requests_type_id', 
                '{{%requests}}', 
                'requests_type_id', 
                '{{%type_requests}}', 
                'type_requests_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-requests-requests_account_id', 
                '{{%requests}}', 
                'requests_account_id', 
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
        $this->createIndex('idx-requests-requests_id', '{{%requests}}');
        $this->createIndex('idx-requests-requests_ident', '{{%requests}}');
        
        $this->addForeignKey('fk-requests-requests_type_id', '{{%requests}}');
        
        $this->addForeignKey('fk-personal_account-requests_account_id', '{{%requests}}');
        
        $this->dropTable('{{%requests}}');
        $this->dropTable('{{%type_requests}}');
    }

}
