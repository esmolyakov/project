<?php

    use yii\db\Migration;

/**
 * Организация
 */
class m180901_105035_table_organizations extends Migration
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
        
        $this->createTable('{{%organizations}}', [
            'organizations_id' => $this->primaryKey(),
            'organizations_name' => $this->string(100)->notNull(),
            'organizations_inn' => $this->string(100)->notNull(),
            'organizations_kpp' => $this->string(100)->notNull(),
            'organizations_adress' => $this->string(255)->notNull(),
            'organizations_email' => $this->string(150)->notNull(),
            'organizations_notice' => $this->tinyInteger()->notNull(),
            'organizations_penalties' => $this->tinyInteger()->notNull(),
            'organizations_penalty_type' => $this->string(100)->notNull(),
            'organizations_penalty_start_date' => $this->string(100)->notNull(),
            'organizations_penalty_start_day' => $this->integer()->notNull(),
            'organizations_name_penalty_start_month' => $this->string(100)->notNull(),
            'organizations_name_penalty_percent' => $this->decimal(10,2)->notNull(),
            'organizations_name_rate_cb' => $this->decimal(10,2)->notNull(),
            'organizations_name_penalty_stop' => $this->tinyInteger()->notNull(),
            'organizations_name_penalty_limit' => $this->tinyInteger()->notNull(),
            'organizations_name_penalty_limit_type' => $this->string(255)->notNull(),
            'organizations_name_penalty_limit_date' => $this->string(255),
            'organizations_name_penalty_limit_all' => $this->string(255)->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-organizations-organizations_id', '{{%organizations_id}}', 'organizations_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organizations}}');
    }

}
