<?php

    use yii\db\Migration;
    use app\models\PersonalAccount;

/**
 * Лицевой счет
 */
class m180901_095338_table_personal_account extends Migration
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
        
        $this->createTable('{{%personal_account}}', [
            'account_id' => $this->primaryKey(),
            'account_number' => $this->string(70)->unique()->notNull(),
            'account_balance' => $this->decimal(10,2)->notNull(),
            'account_organization_id' => $this->integer(),
            'personal_clients_id' => $this->integer(),
            'personal_rent_id' => $this->integer(),
            'personal_house_id' => $this->integer(),
            'isActive' => $this->tinyInteger()->defaultValue(PersonalAccount::STATUS_DISABLED),
        ], $table_options);
        
        $this->createIndex('idx-personal_account-account_id', '{{%personal_account}}', 'account_id');
        $this->createIndex('idx-personal_account-account_number', '{{%personal_account}}', 'account_number');
                
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-personal_account-account_id', '{{%personal_account}}');
        $this->dropIndex('idx-personal_account-account_number', '{{%personal_account}}');

        
        $this->dropForeignKey('fk-personal_account-personal_clients_id', '{{%personal_account}}');
        $this->dropForeignKey('fk-personal_account-personal_rent_id', '{{%personal_account}}');
        $this->dropForeignKey('fk-personal_account-account_organization_id', '{{%personal_account}}');
        $this->dropForeignKey('fk-personal_account-personal_house_id', '{{%personal_account}}');
        $this->dropForeignKey('fk-personal_account-bind-account_id', '{{%personal_account}}');
        
        $this->dropTable('{{%personal_account}}');
    }

}
