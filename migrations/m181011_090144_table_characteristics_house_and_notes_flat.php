<?php

    use yii\db\Migration;

/**
 * Характеристика для домов
 * Примечания к квартирам
 */
class m181011_090144_table_characteristics_house_and_notes_flat extends Migration
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

        // Характеристики дома
        $this->createTable('{{%characteristics_house}}', [
            'characteristics_id' => $this->primaryKey(),
            'characteristics_house_id' => $this->integer()->notNull(),
            'characteristics_name' => $this->string(255)->notNull(),
            'characteristics_value' => $this->string(170)->notNull(),
        ]);
        $this->createIndex('idx-characteristics_house-characteristics_id', '{{%characteristics_house}}', 'characteristics_id');
        
        // Примечания для квартиры
        $this->createTable('{{%notes_flat}}', [
            'notes_id' => $this->primaryKey(),
            'notes_flat_id' => $this->integer()->notNull(),
            'notes_name' => $this->string(170)->notNull(),
        ]);
        $this->createIndex('idx-notes_flat-notes_id', '{{%notes_flat}}', 'notes_id');
        
        
        $this->addForeignKey(
                'fk-characteristics_house-characteristics_house_id', 
                '{{%characteristics_house}}', 
                'characteristics_house_id', 
                '{{%houses}}', 
                'houses_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-notes_flat-notes_flat_id', 
                '{{%notes_flat}}', 
                'notes_flat_id', 
                '{{%flats}}', 
                'flats_id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-characteristics_house-characteristics_id', '{{%characteristics_house}}');
        $this->dropIndex('idx-notes_flat-notes_id', '{{%notes_flat}}');
        
        $this->dropForeignKey('fk-characteristics_house-characteristics_house_id', '{{%characteristics_house}}');
        $this->dropForeignKey('fk-notes_flat-notes_flat_id', '{{%notes_flat}}');
        
        $this->dropTable('{{%characteristics_house}}');
        $this->dropTable('{{%notes_flat}}');
        
    }

}
