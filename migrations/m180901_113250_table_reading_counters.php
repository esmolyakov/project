<?php

    use yii\db\Migration;

/**
 * Показания приборов учета
 */
class m180901_113250_table_reading_counters extends Migration
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
        
        $this->createTable('{{%reading_counters}}', [
            'reading_id' => $this->integer()->notNull(),
            'reading_counter_id' => $this->integer()->notNull(),
            'readings_indication' => $this->string(50)->notNull(),
            'date_reading' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-reading_counters-reading_counter_id', '{{%reading_counters}}', 'reading_counter_id');
        
        $this->addForeignKey(
                'fk-reading_counters-reading_counter_id', 
                '{{%reading_counters}}', 
                'reading_counter_id', 
                '{{%counters}}', 
                'counters_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-reading_counters-reading_counter_id', '{{%reading_counters}}');
        $this->dropForeignKey('fk-reading_counters-reading_counter_id', '{{%reading_counters}}');
        $this->dropTable('{{%reading_counters}}');
    }

}
