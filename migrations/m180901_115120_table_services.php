<?php

use yii\db\Migration;

/**
 * Усуги
 */
class m180901_115120_table_services extends Migration
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
        
        $this->createTable('{{%services}}', [
            'services_id' => $this->primaryKey(),
            'services_name' => $this->string(255)->notNull(),
            'services_category_id' => $this->integer()->notNull(),
            'isPay' => $this->tinyInteger(),
            'isServices' => $this->tinyInteger(),
            'services_image' => $this->string(255)->notNull(),
            'services_description' => $this->text(1000),
        ], $table_options);
        $this->createIndex('idx-services-services_id', '{{%services}}', 'services_id');
        $this->createIndex('idx-services-services_name', '{{%services}}', 'services_name');
        
        $this->createTable('{{%category_services}}', [
            'category_id' => $this->primaryKey(),
            'category_name' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-category_services-category_id', '{{%category_services}}', 'category_id');
        $this->createIndex('idx-category_services-category_name', '{{%category_services}}', 'category_name');
        
        
        $this->addForeignKey(
                'fk-services-services_category_id', 
                '{{%services}}', 
                'services_category_id', 
                '{{%category_services}}', 
                'category_id', 
                'RESTRICT',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-services-services_id', '{{%services}}');
        $this->dropIndex('idx-services-services_name', '{{%services}}');

        $this->dropIndex('idx-category_services-category_id', '{{%category_services}}');
        $this->dropIndex('idx-category_services-category_name', '{{%category_services}}');
        
        $this->dropForeignKey('fk-services-services_category_id', '{{%services}}');
        
        $this->dropTable('{{%category_services}}');
        $this->dropTable('{{%services}}');

    }

}
