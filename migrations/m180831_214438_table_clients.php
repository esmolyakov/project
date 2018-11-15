<?php

    use yii\db\Migration;

/**
 * Собственники
 */
class m180831_214438_table_clients extends Migration
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
        
        $this->createTable('{{%clients}}', [
            'clients_id' => $this->primaryKey(),
            'clients_name' => $this->string(70)->notNull(),
            'clients_second_name' => $this->string(70)->notNull(),
            'clients_surname' => $this->string(70)->notNull(),
            'clients_mobile' => $this->string(70)->unique()->notNull(),
            'clients_phone' => $this->string(70)->unique(),
        ], $table_options);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-clients-clients_id', '{{%clients}}');
        $this->dropTable('{{%clients}}');
    }

}
