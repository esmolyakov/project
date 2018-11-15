<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;
    use yii\helpers\Html;
    use app\models\TypeRequests;
    use app\models\CommentsToRequest;
    use app\models\StatusRequest;
    use app\models\Employers;
    use app\helpers\FormatHelpers;
    use app\models\Image;

/**
 * Заяки
 */
class Requests extends ActiveRecord
{

    const SCENARIO_ADD_REQUEST = 'add_record';
    
    const ACCEPT_YES = 1;
    const ACCEPT_NO = 0;
    
    // Для загружаемых файлов
    public $gallery;

    
    public function behaviors() {
        return [
            
            TimestampBehavior::className(),
            
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],            
        ];
    }
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'requests';
    }
    
    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['requests_type_id', 'requests_comment', 'requests_phone'], 'required', 'on' => self::SCENARIO_ADD_REQUEST],
            
            [
                'requests_phone', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
                'on' => self::SCENARIO_ADD_REQUEST,
            ],
            
            [['gallery'], 'file', 
                'extensions' => 'png, jpg, jpeg', 
                'maxFiles' => 4, 
                'maxSize' => 256 * 1024,
                'mimeTypes' => 'image/*',                
                'on' => self::SCENARIO_ADD_REQUEST,
            ],
            [['requests_comment'], 'string', 'on' => self::SCENARIO_ADD_REQUEST],
            [['requests_comment'], 'string', 'min' => 10, 'max' => 255, 'on' => self::SCENARIO_ADD_REQUEST],
            
            ['requests_grade', 'integer'],
            
        ];
    }
    
    /*
     * Связь с талиблицей Виды заявок
     */
    public function getTypeRequest() {
        return $this->hasOne(TypeRequests::className(), ['type_requests_id' => 'requests_type_id']);
    }       
    
    /*
     * Связь с таблицей комментариев к заявке
     */
    public function getComment() {
        return $this->hasMany(CommentsToRequest::className(), ['comments_request_id' => 'requests_id']);
    }
    
    /*
     * Связь с таблицей прикрепленных фотографий
     */
    public function getImage() {
        return $this->hasMany(Image::className(), ['itemId' => 'requests_id'])->andWhere(['modelName' => 'Requests']);
    }
        
    /*
     * Сохранение новой заявки
     * 
     * @param integer $accoint_id ID заявки
     */
    public function addRequest($accoint_id) {
        
        if (!is_numeric($accoint_id)) {
            Yii::$app->session->setFlash('request', [
                'success' => false,
                'error' => 'При формировании заявки возникла ошибка. Обновите страницу и повторите действия еще раз',
            ]);
            return false;
        }
        
        $account = PersonalAccount::findByAccountID($accoint_id);
        
        /* Формирование идентификатора для заявки:
         *      последние 7 символов лицевого счета - 
         *      последние 6 символов даты в unix - 
         *      тип заявки
         */
        
        $date = new \DateTime();
        $int = $date->getTimestamp();
        
        $request_numder = substr($account->account_number, 4) . '-' . substr($int, 5) . '-' . str_pad($this->requests_type_id, 2, 0, STR_PAD_LEFT);      
        
        if ($this->validate()) {
            $this->requests_ident = $request_numder;
            $this->requests_account_id = $accoint_id;
            $this->status = StatusRequest::STATUS_NEW;
            $this->is_accept = false;
            Yii::$app->session->setFlash('request', [
                'success' => true,
                'message' => 'Ваша заявка была успешно  сформирована на лицевой счет №' . $account->account_number . '<br />'
                    . 'Номер вашей заявки №' . $request_numder . '<br />'
                    . 'Ознакомиться с деталями заявки можно пройдя по ' . Html::a('ссылке', ['requests/view-request', 'request_numder' => $request_numder])
            ]);
            return $this->save() ? true : false;
        }
        Yii::$app->session->setFlash('request', [
                'success' => false,
                'error' => 'При формировании заявки возникла ошибка. Обновите страницу и повторите действия еще раз',
        ]);
        return false;
    }
    
    /*
     * Добавление оценки для заявки
     * 
     * @param integer $score Оценка
     */
    public function addGrade($score) {

        if (!is_numeric($score)) {
            return false;
        }
        
        $this->requests_grade = $score;
        $this->save(false);
        return true;
        
    }
    
    /*
     * Получить тип заявки по ID
     */
    public function getNameRequest() {
        return ArrayHelper::getValue(TypeRequests::getTypeNameArray(), $this->requests_type_id);
    }
    
    /*
     * Поиск заявки по ID
     */
    public static function findByID($request_id) {
        return self::find()
                ->andWhere(['requests_id' => $request_id])
                ->one();
    }
    
    /*
     * Поиск заявки по его уникальному номеру
     */
    public static function findRequestByIdent($request_numder) {
        
        $request = (new \yii\db\Query)
                ->from('requests as r')
                ->join('LEFT JOIN', 'type_requests as tr', 'r.requests_type_id = tr.type_requests_id')                
                ->where(['requests_ident' => $request_numder])
                ->one();
        
        return $request;
        
    }
    
     /*
     * Поиск заявки по ID лицевого счета
     * @param ActiveQuery
     */
    public static function findByAccountID($account_id) {
        
        $requests = self::find()
                ->with('image')
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        return $requests;
    }

    /*
     * Загрузка прикрепленных к заявке изображений
     */
    public function uploadGallery() {
        
        if ($this->validate()) {
            foreach ($this->gallery as $file) {
                $path = 'upload/store' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $this->attachImage($path);
                @unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function switchStatus($status) {
        
        $this->status = $status;
        
        if ($status == StatusRequest::STATUS_CLOSE) {
            $this->date_closed = time();
        } else {
            $this->date_closed = null;
            $this->requests_grade = null;
        }
        
        return $this->save() ? true : false;
        
    }
    
    /*
     * @param integer ID заявки
     */
    public function getId() {
        return $this->requests_id;
    }
    
    /*
     * @param integet ID Лицевого счета заявки
     */
    public function getAccount() {
        return $this->requests_account_id;
    }
    
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'requests_id' => 'Requests ID',
            'requests_ident' => 'Номер заявки',
            'requests_type_id' => 'Вид заявки',
            'requests_comment' => 'Описание',
            'requests_dispatcher_id' => 'Назначена',
            'requests_specialist_id' => 'Исполнитель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата закрытия',
            'status' => 'Статус',
            'requests_account_id' => 'Номер лицевого счета',
            'requests_phone' => 'Контактный телефон',
            'gallery' => 'Прикрепить файлы',
            'is_accept' => 'Принято на рассмотрение',
            'requests_grade' => 'Оценка',
        ];
    }
}
