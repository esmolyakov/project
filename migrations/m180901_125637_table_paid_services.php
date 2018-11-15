<?php

    use yii\db\Migration;
    use app\models\StatusRequest;

/**
 * Платные услуги
 */
class m180901_125637_table_paid_services extends Migration
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
        
        $this->createTable('{{%paid_services}}', [
            'services_id' => $this->primaryKey(),
            'services_number' => $this->string(16)->notNull(),
            'services_name_services_id' => $this->integer()->notNull(),
            'services_comment' => $this->text(255)->notNull(),
            'services_phone' => $this->string(50)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(StatusRequest::STATUS_NEW),
            'services_dispatcher_id' => $this->integer(),
            'services_specialist_id' => $this->integer(),
            'services_account_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-paid_services-services_id', '{{%paid_services}}', 'services_id');
        $this->createIndex('idx-paid_services-services_number', '{{%paid_services}}', 'services_number');
        
        $this->addForeignKey(
                'fk-paid_services-services_name_services_id', 
                '{{%paid_services}}', 
                'services_name_services_id', 
                '{{%services}}', 
                'services_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-paid_services-services_account_id', 
                '{{%paid_services}}', 
                'services_account_id', 
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
        $this->dropIndex('idx-paid_services-services_id', '{{%paid_services}}');
        $this->dropIndex('idx-paid_services-services_number', '{{%paid_services}}');
        $this->dropForeignKey('fk-paid_services-services_name_services_id', '{{%paid_services}}');
        $this->dropForeignKey('fk-paid_services-services_account_id', '{{%paid_services}}');
        $this->dropTable('{{%paid_services}}');
    }
    
}
