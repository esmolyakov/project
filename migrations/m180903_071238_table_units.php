<?php

    use yii\db\Migration;

/**
 * Единицы измерения
 */
class m180903_071238_table_units extends Migration
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

        $this->createTable('{{%units}}', [
            'units_id' => $this->primaryKey(),
            'units_name' => $this->integer()->notNull(),
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%units}}');
    }

}
