<?php

    use yii\db\Migration;
    use yii\db\Expression;    
    use app\models\News;    

/**
 * Партнеры (Контрагенты)
 * Новости
 * Рубрика / Тип публикации
 */
class m181001_072514_table_news extends Migration
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
        
        $this->createTable('{{%partners}}', [
            'partners_id' => $this->primaryKey(),
            'partners_name' => $this->string(170)->notNull(),
            'partners_adress' => $this->string(255)->notNull(),
        ]);
        $this->createIndex('idx-partners-partners_id', '{{%partners}}', 'partners_id');

        $this->createTable('{{%rubrics}}', [
            'rubrics_id' => $this->string(70)->notNull(),
            'rubrics_name' => $this->string(170)->notNull(),
            'PRIMARY KEY (rubrics_id)',
        ]);
        
        $this->batchInsert('{{%rubrics}}', 
                ['rubrics_id', 'rubrics_name'], [
                    ['important_information', 'Важная информация'],
                    ['special_offers', 'Специальные предложения'],
                    ['house_news', 'Новости дома']
                ]
        );
        
        $this->createTable('{{%news}}', [
            'news_id' => $this->primaryKey(),
            'news_type_rubric_id' => $this->string(70)->notNull(),
            'news_title' => $this->string(255)->notNull(),
            'news_text' => $this->text(5000)->notNull(),
            'news_preview' => $this->string(255)->notNull(),
            'news_house_id' => $this->integer(),
            'news_user_id' => $this->integer()->notNull(),
            'news_partner_id' => $this->integer(),
            'news_status' => $this->integer()->notNull()->defaultValue(News::FOR_ALL),
            'isPrivateOffice' => $this->integer()->notNull(),
            'isSMS' => $this->tinyInteger(),
            'isEmail' => $this->tinyInteger(),
            'isPush' => $this->tinyInteger(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'slug' => $this->string(255),
            'isAdvert' => $this->tinyInteger()->defaultValue(0),
        ]);
        $this->createIndex('idx-news-news_id', '{{%news}}', 'news_id');
        
        
        $this->addForeignKey(
                'fk-news-news_type_rubric_id', 
                '{{%news}}', 
                'news_type_rubric_id', 
                '{{%rubrics}}', 
                'rubrics_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-news-news_partner_id', 
                '{{%news}}', 
                'news_partner_id', 
                '{{%partners}}', 
                'partners_id', 
                'SET NULL',
                'CASCADE'
        );
        
        /* $this->addForeignKey(
                'fk-news-news_house_id', 
                '{{%news}}', 
                'news_house_id', 
                '{{%houses}}', 
                'houses_id', 
                'SET NULL',
                'CASCADE'
        ); */
        
        /* $this->addForeignKey(
                'fk-news-news_user_id', 
                '{{%news}}', 
                'news_user_id', 
                '{{%user}}', 
                'user_id', 
                'SET NULL',
                'CASCADE'
        ); */
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-news-news_id', '{{%news}}');
        $this->dropIndex('idx-rubrics-rubrics_id', '{{%rubrics}}');
        $this->dropIndex('idx-partners-partners_id', '{{%partners}}', 'partners_id');
        $this->dropForeignKey('fk-rubrics-rubrics_id', '{{%news}}');
        $this->dropForeignKey('fk-news-news_partner_id', '{{%news}}');
        $this->dropForeignKey('fk-news-news_house_id', '{{%news}}');
        $this->dropForeignKey('fk-news-news_user_id', '{{%news}}');
        $this->dropTable('{{%partners}}');
        $this->dropTable('{{%rubrics}}');
        $this->dropTable('{{%news}}');
    }

}
