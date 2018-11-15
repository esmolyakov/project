<?php

    use yii\db\Migration;
    use app\models\Flats;

/**
 * Квартиры
 * Дома
 * Жилой комплекс
 */
class m180901_103657_table_houses extends Migration
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
        
        // Жилой комплекс
        $this->createTable('{{%housing_estates}}', [
            'estate_id' => $this->primaryKey(),
            'estate_name' => $this->string(170)->notNull(),
            'estate_town' => $this->string(70)->notNull(),
        ]);
        $this->createIndex('idx-housing_estates-estate_id', '{{%housing_estates}}', 'estate_id');
        
        // Дома
        $this->createTable('{{%houses}}', [
            'houses_id' => $this->primaryKey(),
            'houses_estate_name_id' => $this->integer()->notNull(),
            'houses_street' => $this->string(100)->notNull(),
            'houses_number_house' => $this->string(10)->notNull(),
            'houses_description' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-houses-houses_id', '{{%houses}}', 'houses_id');
        
        // Квартиры
        $this->createTable('{{%flats}}', [
            'flats_id' => $this->primaryKey(),
            'flats_house_id' => $this->integer()->notNull(),
            'flats_porch' => $this->integer()->notNull(),
            'flats_floor' => $this->integer()->notNull(),
            'flats_number' => $this->integer()->notNull(),
            'flats_rooms' => $this->integer()->notNull(),
            'flats_square' => $this->integer()->notNull(),
            'flats_account_id' => $this->integer(),
            'flats_client_id' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue(Flats::STATUS_DEBTOR_NO),
        ], $table_options);
        $this->createIndex('idx-flats-flats_id', '{{%flats}}', 'flats_id');
        
        $this->addForeignKey(
                'fk-houses-houses_estate_name_id', 
                '{{%houses}}', 
                'houses_estate_name_id', 
                '{{%housing_estates}}', 
                'estate_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-flats-flats_house_id', 
                '{{%flats}}', 
                'flats_house_id', 
                '{{%houses}}', 
                'houses_id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropIndex('idx-housing_estates-estate_id', '{{%housing_estates}}');
        $this->dropIndex('idx-houses-houses_id', '{{%houses}}');
        $this->dropIndex('idx-flats-flatsid', '{{%flats}}');
        
        $this->addForeignKey('fk-houses-houses_estate_name_id', '{{%houses}}');
        $this->addForeignKey('fk-flats-flats_house_id', '{{%flats}}');
                
        $this->dropTable('{{%flats}}');
        $this->dropTable('{{%houses}}');
        $this->dropTable('{{%housing_estates}}');
    }

}
