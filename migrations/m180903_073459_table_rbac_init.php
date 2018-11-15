<?php

    use yii\db\Migration;
    use yii\rbac\DbManager;
    use \yii\base\InvalidConfigException;

/**
 * Создание RBAC таблиц
 */
class m180903_073459_table_rbac_init extends Migration
{
    /*
     * Проверка настройки компонента «authManager» 
     * для использования базы данных перед выполнением этой миграции.
     */
    public function getAuthManger()
    {    
        $auth_manager = Yii::$app->getAuthManager();
        if (!$auth_manager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $auth_manager;
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $auth_manager = $this->getAuthManger();
        $this->db = $auth_manager->db;
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($auth_manager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ], $table_options);
        
        $this->createTable($auth_manager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $auth_manager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ]);
        $this->createIndex('idx-auth_item-type', $auth_manager->itemTable, 'type');
        
        $this->createTable($auth_manager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $auth_manager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $auth_manager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $table_options);
        
        $this->createTable($auth_manager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $auth_manager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $table_options);
        
        // Роли
        $this->batchInsert($auth_manager->itemTable, [
            'name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], [
                ['clients', '1', 'Собственник', NULL, NULL, time(), time()],
                ['clients_rent', '1', 'Арендатор', NULL, NULL, time(), time()],
                ['dispatcher', '1', 'Сотрудник (Диспетчер)', NULL, NULL, time(), time()],
                ['specialist', '1', 'Специалист', NULL, NULL, time(), time()],
                ['administrator', '1', 'Администратор', NULL, NULL, time(), time()],
                ['AddNewRent', '2', 'Добавить нового аредатора', NULL, NULL, time(), time()],
                ['ViewInfoRent', '2', 'Редактирование информации об Арендаторе (блок Контактные даныые арендатора)', NULL, NULL, time(), time()],
                ['Vote', '2', 'Голосование', NULL, NULL, time(), time()],
            ]
        );
        
        
        // Права
        $this->batchInsert($auth_manager->itemChildTable, [
            'parent', 'child'], [
                ['clients', 'AddNewRent'],
                ['clients', 'ViewInfoRent'],
                ['clients', 'Vote'],
            ]
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth_manager = $this->getAuthManger();
        $this->db = $auth_manager->db;
        
        $this->dropTable($auth_manager->assignmentTable);
        $this->dropTable($auth_manager->itemChildTable);
        $this->dropTable($auth_manager->itemTable);
        $this->dropTable($auth_manager->ruleTable);
    }

}
