<?php

    use yii\db\Migration;

/**
 * Тарифы
 */
class m180903_071239_table_rates extends Migration
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

        $this->createTable('{{%rates}}', [
            'rates_id' => $this->primaryKey(),
            'rates_service_id' => $this->integer()->notNull(),
            'rates_unit_id' => $this->integer()->notNull(),
            'rates_cost' => $this->decimal(10,2)->notNull(),
        ]);
        
        $this->createIndex('idx-rates-rates_id', '{{%rates}}', 'rates_id');
        
        $this->addForeignKey(
                'fk-rates-rates_service_id', 
                '{{%rates}}', 
                'rates_service_id', 
                '{{%services}}', 
                'services_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-rates-rates_unit_id', 
                '{{%rates}}', 
                'rates_unit_id', 
                '{{%units}}', 
                'units_id', 
                'RESTRICT',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-rates-rates_id', '{{%rates}}');
        $this->dropForeignKey('fk-rates-rates_service_id', '{{%rates}}');
        $this->dropForeignKey('fk-rates-rates_unit_id', '{{%rates}}');
        $this->dropTable('{{%rates}}');
    }

}
