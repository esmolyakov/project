<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\PaidServices;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\StatusRequest;

/**
 * Заявка на платную услугу
 */
class PaidRequestForm extends Model {
   
    public $servise_category;
    public $servise_name;
    public $phone;
    public $flat;
    public $description;
    
    /*
     *  Правила валидации
     */
    public function rules() {
        return [
            [['servise_category', 'servise_name', 'phone', 'description', 'flat'], 'required'],
            
            ['description', 'string', 'min' => 10, 'max' => 255],
//            ['description', 'match',
//                'pattern' => '/^[А-Яа-яЁёA-Za-z0-9\_\-\@\.]+$/iu',
//                'message' => 'Поле "{attribute}" может содержать только буквы русского и английского алфавита, цифры, знаки "-", "_"',
//            ],            
            
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
    
    public function save() {
        
        $account_id = PersonalAccount::findByFlatId($this->flat);
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $paid_request = new PaidServices();

            /* Формирование идентификатора для заявки:
             *      последние 7 символов лицевого счета - 
             *      последние 6 символов даты в unix - 
             *      код вида платной заявки
             *      код наименования платной заявки
             */
            
            $date = new \DateTime();
            $int = $date->getTimestamp();

            $numder = substr($account_id['account_number'], 4) . 
                    '-' . substr($int, 5) . 
                    '-' . str_pad($this->servise_category, 2, 0, STR_PAD_LEFT) . 
                    '-' . str_pad($this->servise_name, 2, 0, STR_PAD_LEFT);
            
            $paid_request->services_number = $numder;
            $paid_request->services_category_services_id = $this->servise_category;
            $paid_request->services_name_services_id = $this->servise_name;
            $paid_request->services_phone = $this->phone;
            $paid_request->services_comment = $this->description;
            $paid_request->status = StatusRequest::STATUS_NEW;
            $paid_request->services_account_id = $account_id['account_id'];
            
            $paid_request->save();
            
            $transaction->commit();
            
            return $numder;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
    }
    
    /*
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'servise_category' => 'Вид услуги',
            'servise_name' => 'Наименование услуги',
            'phone' => 'Мобильный телефон',
            'flat' => 'Адрес',
            'description' => 'Описание заявки',
        ];
    }
    
}
