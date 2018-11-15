<?php

    use yii\db\Migration;
    use app\models\Rents;

/**
 * Арендаторы
 */
class m180901_090529_table_rents extends Migration
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
        
        $this->createTable('{{%rents}}', [
            'rents_id' => $this->primaryKey(),
            'rents_name' => $this->string(70)->notNull(),
            'rents_second_name' => $this->string(70)->notNull(),
            'rents_surname' => $this->string(70)->notNull(),
            'rents_mobile' => $this->string(70)->unique()->notNull(),
            'rents_mobile_more' => $this->string(70)->unique(),
            'rents_clients_id' => $this->integer()->notNull(),
            'isActive' => $this->tinyInteger()->defaultValue(Rents::STATUS_ENABLED),
        ], $table_options);
        
        $this->createIndex('idx-rents-rents_id', '{{%rents}}', 'rents_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-rents-rents_id', '{{%rents}}');
        $this->dropForeignKey('fk-rents-rents_clients_id', '{{%rents}}');
        $this->dropTable('{{%rents}}');
    }

}
