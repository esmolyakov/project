<?php

use yii\db\Migration;

/**
 * Class m180901_114651_table_image
 */
class m180901_114651_table_image extends Migration
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
        
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'filePath' => $this->text(400),
            'itemId' => $this->integer(),
            'isMain' => $this->tinyInteger(),
            'modelName' => $this->string(150),
            'urlAlias' => $this->text(400),
            'name' => $this->string(70),
        ], $table_options);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%image}}');
    }

}
