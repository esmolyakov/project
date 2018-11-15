<?php

    use yii\db\Migration;
    use app\models\User;

/**
 * Пользователи
 */
class m180831_193134_table_users extends Migration
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
        
        $this->createTable('{{%user}}', [
            'user_id' => $this->primaryKey(),
            'user_login' => $this->string(70)->unique()->notNull(),
            'user_password' => $this->string(255)->notNull(),
            'user_email' => $this->string(150)->unique()->notNull(),
            'user_mobile' => $this->string(70)->unique()->notNull(),
            'user_photo' => $this->string(255),
            'user_check_email' => $this->tinyInteger()->defaultValue('1'),
            'user_authkey' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'email_confirm_token' => $this->string(255),
            'password_reset_token' => $this->string(255),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_DISABLED),
            'last_login' => $this->integer(),
            'user_client_id' => $this->integer(),
            'user_rent_id' => $this->integer(),
        ], $table_options);
        $this->createIndex('idx-user-user_id', '{{%user}}', 'user_id');
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user-user_id', '{{%user}}');
        $this->dropForeignKey('fk-user-user_client_id', '{{%user}}');
        $this->dropForeignKey('fk-user-user_rent_id', '{{%user}}');
        $this->dropForeignKey('fk-user-bind-user_id', '{{%user}}');
        $this->dropTable('{{%user}}');
    }

}
