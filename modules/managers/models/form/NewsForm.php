<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\News;
    use yii\helpers\HtmlPurifier;

/* 
 * Создание новой новости
 */
class NewsForm extends Model {
    
    // Статус публикации
    public $status;
    // Тип рубрикм
    public $rubric;
    // Заголовок
    public $title;
    // Текс новости
    public $text;
    // Изображение новости
    public $preview;
    // Дом
    public $house;
    // Тип публикации
    public $isPrivateOffice;
    // Тип оповещение (СМСб Email, Push)
    public $isNotice;
    // Пользователь
    public $user;
    // Загружаемые файлы
    public $files;
    // Переключатель на рекламную публикацию
    public $isAdvert = false;
    // Контрагент
    public $partner;


    public function rules() {
        return [
            [[
                'status',
                'rubric', 'title', 'text', 'preview', 
                'house', 
                'isPrivateOffice', 
                'user'], 'required'],
            
            ['title', 'string', 'min' => '6', 'max' => '255'],
                        
            ['text', 'string', 'min' => '10', 'max' => '10000'],
            
            [['title', 'text'], 'filter', 'filter' => 'trim'],
            
            [['status', 'house', 'isPrivateOffice', 'isNotice', 'partner'], 'integer'],
            
            [['preview'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['preview'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            [['files'], 'file', 'extensions' => 'doc, docx, pdf, xls, xlsx, ppt, pptx, txt', 'maxFiles' => 4],
            
            [['partner', 'isAdvert'], 'integer'],
            
            // Поле контрагент обязательно для заполнения, если стоит переключатель "рекламная запись"
            ['partner', 'required', 'when' => function($model) {
                return $model->isAdvert = true;
            }],            
            
        ];
    }
    
    /*
     * Сохраняем запись публикации
     * 
     * @param string $file Превью новости
     * @param string $files Прикрепленные изображения
     */
    public function save($file, $files) {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $add_news = new News();
            $add_news->news_type_rubric_id = $this->rubric;
            $add_news->news_title = HtmlPurifier::process(strip_tags($this->title));
            $add_news->news_text = HtmlPurifier::process(strip_tags($this->text));
            $add_news->isPrivateOffice = $this->isPrivateOffice;
            
            // Если значение дома не задано, то публикацию сохраняем как "для всех" (null)
            $add_news->news_house_id = $this->house ? $this->house : null;
            $add_news->news_status = $this->status;
            // Пользователь, создавший публикацию
            $add_news->news_user_id = Yii::$app->user->identity->id;
            // Сохраняем превью публикации
            $add_news->uploadImage($file);
            // Сохраняем прикрепленные изображения
            $add_news->uploadFiles($files);
            
            $add_news->isSMS = $this->isNotice[0] ? true : false;
            $add_news->isEmail = $this->isNotice[1] ? true : false;
            $add_news->isPush = $this->isNotice[2] ? true : false;
            
            if ($this->isAdvert == 1) {
                $add_news->isAdvert = 1;
                $add_news->news_partner_id = $this->partner;
            } else {
                $add_news->isAdvert = 0;
                $add_news->news_partner_id = null;
            }
            
            if(!$add_news->save()) {
                throw new \yii\db\Exception('Ошибка добавления новости. Ошибка: ' . join(', ', $add_news->getFirstErrors()));
//                return ['error' => join(', ', $add_news->getFirstErrors())];
            }
            
            
            $transaction->commit();
            
            return $add_news->slug;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
    }

    public function attributeLabels() {
        return [
            'status' => 'Статус публикации',
            'rubric' => 'Тип публикации',
            'title' => 'Заголовок публикации',
            'text' => 'Текст публикации',
            'preview' => 'Превью',
            'house' => 'Адрес',
            'isPrivateOffice' => 'Уведомления',
            'isSMS' => 'СМС',
            'isEmail' => 'Email',
            'isPush' => 'Push',
            'user' => 'Пользователь',
            'files' => 'Прикрепленные файлы',
            'isAdvert' => 'Рекламная публикаця',
            'partner' => 'Котрагент',
        ];
    }
    
    
}
?>
