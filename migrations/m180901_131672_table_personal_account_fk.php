<?php

use yii\db\Migration;

/**
 * Class m180901_131672_table_personal_account_fk
 */
class m180901_131672_table_personal_account_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
                'fk-personal_account-personal_clients_id', 
                '{{%personal_account}}', 
                'personal_clients_id', 
                '{{%clients}}', 
                'clients_id', 
                'SET NULL',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-personal_account-personal_rent_id', 
                '{{%personal_account}}', 
                'personal_rent_id', 
                '{{%rents}}', 
                'rents_id', 
                'SET NULL',
                'CASCADE'
        );

        $this->addForeignKey(
                'fk-personal_account-account_organization_id', 
                '{{%personal_account}}', 
                'account_organization_id', 
                '{{%organizations}}', 
                'organizations_id', 
                'RESTRICT',
                'CASCADE'
        );

        $this->addForeignKey(
                'fk-personal_account-personal_house_id', 
                '{{%personal_account}}', 
                'personal_house_id', 
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
        echo "m180901_131672_table_personal_account_fk cannot be reverted.\n";

        return false;
    }

}
