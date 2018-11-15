<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use app\models\Requests;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use app\models\Rents;
    use yii\base\Model;
    use app\models\StatusRequest;

/**
 * Новая заявка
 */
class RequestForm extends Model {
    
    public $type_request;
    public $phone;
    public $flat;
    public $description;
    
     /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['type_request', 'phone', 'description', 'flat'], 'required'],
            
            ['description', 'string', 'min' => 10, 'max' => 255],
            ['description', 'match',
                'pattern' => '/^[А-Яа-яЁёA-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского и английского алфавита, цифры, знаки "-", "_"',
            ],            
            
            ['phone', 'existenceClient'],
            ['phone',
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
            ],
        ];
    }  
    
    /*
     * Проверяет наличие собственника или арендатора по указанному номеру телефона
     */
    public function existenceClient() {
        
        $client = Clients::find()
                ->andWhere(['clients_mobile' => $this->phone])
                ->orWhere(['clients_phone' => $this->phone])
                ->one();

        $rent = Rents::find()
                ->andwhere(['rents_mobile' => $this->phone])
                ->orWhere(['rents_mobile_more' => $this->phone])
                ->one();
        
        if ($client == null && $rent == null) {
            $errorMsg = 'Собственник или арендатор по указанному номеру мобильного телефона на найден. Укажите существующий номер телефона';
            $this->addError('phone', $errorMsg);
        }

    }
    
    /*
     * Метод сохранения заявки
     */
    public function save() {
        
        $account_id = PersonalAccount::findByFlatId($this->flat);
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $request = new Requests();

            /* Формирование идентификатора для заявки:
             *      последние 7 символов лицевого счета - 
             *      последние 6 символов даты в unix - 
             *      тип заявки
             */
            $date = new \DateTime();
            $int = $date->getTimestamp();

            $numder = substr($account_id['account_number'], 4) . '-' . substr($int, 5) . '-' . str_pad($this->type_request, 2, 0, STR_PAD_LEFT);
            $request->requests_ident = $numder;
            $request->requests_type_id = $this->type_request;
            $request->requests_phone = $this->phone;
            $request->requests_comment = $this->description;
            $request->status = StatusRequest::STATUS_NEW;
            $request->is_accept = 1;
            $request->requests_account_id = $account_id['account_id'];
            
            $request->save();
            
            $transaction->commit();
            
            return $numder;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
    }
    
    /*
     * Метод возвращает ID Собственника/Арендатора и его лицевой счет
     */
    public function findClientPhone($phone) {
        
        $client = \app\models\Clients::find()
                ->where(['LIKE', new \yii\db\Expression('REPLACE(clients_mobile, " ", "")'), $phone])
                ->orWhere(['LIKE', new \yii\db\Expression('REPLACE(clients_phone, " ", "")'), $phone])
                ->asArray()
                ->one();

        $rent = \app\models\Rents::find()
                ->where(['LIKE', new \yii\db\Expression('REPLACE(rents_mobile, " ", "")'), $phone])
                ->orWhere(['LIKE', new \yii\db\Expression('REPLACE(rents_mobile_more, " ", "")'), $phone])
                ->asArray()
                ->one();
        
        if ($client == null && $rent == null) {
            return false;
        } elseif ($client != null && $rent == null) {
            return ['client_id' => $client['clients_id']];
        } elseif ($client == null && $rent != null) {
            return ['client_id' => $rent['rents_clients_id']];
        }
                
    }
    
    public function attributeLabels() {
        return [
            'type_request' => 'Вид заявки',
            'phone' => 'Мобильный телефон',
            'flat' => 'Дом',
            'fullname' => 'Фамилия имя отчество',
            'description' => 'Описание',
        ];
    }
}
