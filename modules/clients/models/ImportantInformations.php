<?php

    namespace app\modules\clients\models;
    use yii\base\Model;
    use app\models\News;
    use app\models\Voting;

/**
 * Важная информация для главной страницы, раздел "Важная информация"
 */
class ImportantInformations extends Model {
    
    // Количество блоков для вывода важной информации
    public $count_news = 6;

    public function informations($living_space) {
        
        $voling_list = Voting::find()
                ->select(['voting_id as slug', 'voting_image as news_preview', 'voting_title as news_title', 'voting_text as news_text', 'created_at'])
                ->andWhere(['voting_house' => $living_space['houses_id']])
                ->andWhere(['status' => false])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
        
        $news_list = News::find()
                ->select(['news_id', 'news_title', 'news_preview', 'news_text', 'created_at', 'slug'])
                ->andWhere([
                    'news_house_id' => $living_space['houses_id']])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->limit($this->count_news - count($voling_list))
                ->all();
        
        $lists = array_merge($voling_list, $news_list);
        
        // Сортируем полученный массив по дате создания записи
        usort($lists, function ($a, $b) {
            return (strtotime($a['created_at']) < strtotime($b['created_at'])) ? 1 : -1;
        });
        
        return $lists;
    }
    
}
