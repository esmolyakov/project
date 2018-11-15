<?php

    use yii\db\Migration;

/**
 * СМС операции
 */
class m181106_142712_table_SMS_operations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%sms_operations}}', [
            'id' => $this->primaryKey(),
            'operations_type' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'sms_code' => $this->integer(5)->notNull(),
            'value' => $this->string(255),
            'date_registration' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(false),
        ]);
        
        $this->createIndex('idx-sms_operations-id', '{{%sms_operations}}', 'id');
        $this->createIndex('idx-sms_operations-user_id', '{{%sms_operations}}', 'user_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx-sms_operations-id', '{{%sms_operations}}');
        $this->dropIndex('idx-sms_operations-user_id', '{{%sms_operations}}');
        $this->dropTable('{{%sms_operations}}');       
    }

}
