<?php

    namespace app\modules\clients\models;
    use yii\base\Model;
    use Yii;
    use app\models\Requests;
    use app\models\PersonalAccount;

/**
 * Форма добавления новой заявки
 */
class AddRequest extends Model {
    
    // Уникальный идентивикатор
    public $request_numder;
    // Тип заявки
    public $request_type;
    // телефон
    public $request_phone;
    // комментарий
    public $request_comment;
    
    // Загружаемые файлы
    public $gallery;

    public function rules() {
        return [
            
            [['request_type', 'request_phone', 'request_comment'], 'required'],
            
            ['request_type', 'integer'],
            
            ['request_comment', 'string', 'min' => 10, 'max' => 1000],
            
            ['request_phone', 'string'],
            
            ['request_phone', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            [['gallery'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
            
            [['gallery'], 'image' /* , 'maxWidth' => 510, 'maxHeight' => 470 */],
            
        ];
    }
    
    public function addRequest($user) {
        
        $account = PersonalAccount::findByAccountNumber($user);
        
        /* Формирование идентификатора для заявки:
         * последние 7 символов лицевого счета - тип заявки
         */
        $this->request_numder = substr($account->account_number, 4) . '-' . str_pad($this->request_type, 2, 0, STR_PAD_LEFT);
        
        $new_requests = new Requests();
        
        if ($new_requests->validate()) {
            $new_requests->requests_ident = $this->request_numder;
            $new_requests->requests_type_id = $this->request_type;
            $new_requests->requests_comment = $this->request_comment;
            $new_requests->requests_phone = $this->request_phone;
            $new_requests->requests_user_id = $user;
            $new_requests->status = Requests::STATUS_NEW;
            $new_requests->is_accept = false;
            
            if ($new_requests->save()) {
                $new_requests->gallery = \yii\web\UploadedFile::getInstances($new_requests, 'gallery');
//                var_dump($this->gallery);die;
                $new_requests->uploadGallery();
            }
            return $new_requests->save() ? $new_requests : null;
        }
        
    }
    

        
    
    public function attributeLabels() {
        return [
            'request_numder' => 'Номер заявки (Идентификатор)',
            'request_type' => 'Вид заявки',
            'request_phone' => 'Телефон',
            'request_comment' => 'Описание',
        ];
    }
}
