<?php

    namespace app\models;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\data\ActiveDataProvider;
    use app\models\StatusRequest;

/**
 * Платные услуги
 */
class PaidServices extends ActiveRecord
{
    
    const SCENARIO_ADD_SERVICE = 'add_record';
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'paid_services';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['services_name_services_id', 'services_category_services_id', 'services_phone', 'services_comment'], 'required', 'on' => self::SCENARIO_ADD_SERVICE],
            
            [
                'services_phone', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
                'on' => self::SCENARIO_ADD_SERVICE,
            ],

            [['services_comment'], 'string', 'on' => self::SCENARIO_ADD_SERVICE],
            [['services_comment'], 'string', 'min' => 10, 'max' => 255, 'on' => self::SCENARIO_ADD_SERVICE],
            
            [['services_name_services_id', 'services_category_services_id', 'created_at', 'updated_at', 'status', 'services_dispatcher_id', 'services_specialist_id', 'services_account_id'], 'integer'],
            [['services_number'], 'string', 'max' => 50],
            [['services_phone'], 'string', 'max' => 50],
        ];
    }
    
    public function getService() {
        return $this->hasOne(Services::className(), ['services_id' => 'services_name_services_id']);
    }

    /*
     * Получить название категории по ID услуги
     */
    public function getNameCategory() {
        $serv = Services::find()->andWhere(['services_id' => $this->services_name_services_id])->one();
        return ArrayHelper::getValue(CategoryServices::getCategoryNameArray(), $serv->services_category_id);
    }
    
    /*
     * Получить название услуги по ID
     */
    public function getNameServices() {
        return ArrayHelper::getValue(Services::getServicesNameArray(), $this->services_name_services_id);
    }    

    /*
     * Получить все заявки, текущего пользователя
     * @param ActiveQuery $all_orders
     */
    public static function getOrderByUder($account_id) {
        
        $query = (new \yii\db\Query())
                ->select('p.services_number, '
                        . 'c.category_name, '
                        . 's.services_name, '
                        . 'p.created_at, p.services_comment, '
                        . 'p.services_specialist_id,'
                        . 'p.status,'
                        . 'p.updated_at')
                ->from('paid_services as p')
                ->join('LEFT JOIN', 'services as s', 's.services_id = p.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as c', 'c.category_id = s.services_category_id')
                ->andWhere(['services_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        $all_orders = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => (Yii::$app->params['countRec']['client']) ? Yii::$app->params['countRec']['client'] : 15,
                ],
            ]);
        
        return $all_orders;
        
    }
    
    /*
     * Сохранение новой платной заявки
     */
    public function addOrder($accoint_id) {
        
        if ($this->validate()) {
        
            /* Формирование идентификатора для заявки:
             *      последние 7 символов лицевого счета - 
             *      последние 6 символов даты в unix - 
             *      тип платной заявки
             */

            $account = PersonalAccount::findByAccountID($accoint_id);

            $date = new \DateTime();
            $int = $date->getTimestamp();

            $order_numder = substr($account->account_number, 4) . '-' . substr($int, 5) . '-' . str_pad($this->services_name_services_id, 2, 0, STR_PAD_LEFT);

            $this->services_number = $order_numder;
            $this->status = StatusRequest::STATUS_NEW;
            $this->services_account_id = $accoint_id;
            $this->save();
            
            Yii::$app->session->setFlash('paid-services', [
                'success' => true,
                'message' => 'Ваша заявка на платную услугу была успешно сформирована на лицевой счет №' . $account->account_number . '<br />' .
                    'Номер вашей заявки №' . $order_numder . '<br />' .
                    'Ознакомиться со списков ваших платных заявок можно пройдя по ' . Html::a('ссылке', ['paid-services/index'])
            ]);
            
            return true;
            
        }

        Yii::$app->session->setFlash('paid-services', [
                'success' => false,
                'error' => 'При формировании заявки на платную услугу возникла ошибка. Обновите страницу и повторите действия еще раз',
        ]);
        return false;
        
    }
    
    public static function findRequestByIdent($request_number) {

        $request = (new \yii\db\Query)
                ->select('ps.services_id as id, ps.services_number as number,'
                        . 'ps.created_at as date_cr, ps.updated_at as date_up, ps.date_closed as date_cl,'
                        . 'ps.services_phone as phone, ps.services_comment as text, '
                        . 'ps.status as status, ps.services_comment as text,'
                        . 'ps.services_grade as grade,'
                        . 'ps.services_dispatcher_id as dispatcher,'
                        . 'ps.services_specialist_id as specialist,'
                        . 'cs.category_name as category, s.services_name as services_name,'
                        . 'he.estate_town as town, h.houses_street as street, h.houses_number_house as number_house,'
                        . 'f.flats_porch as porch, f.flats_floor as floor, f.flats_number as flat')
                ->from('paid_services as ps')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = ps.services_category_services_id')
                ->join('LEFT JOIN', 'services as s', 's.services_id = ps.services_name_services_id')
                ->join('LEFT JOIN', 'employers as ed', 'ed.employers_id = ps.services_dispatcher_id')
                ->join('LEFT JOIN', 'employers as es', 'es.employers_id = ps.services_specialist_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = ps.services_account_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_house_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'housing_estates as he', 'he.estate_id = h.houses_estate_name_id')          
                ->where(['services_number' => $request_number])
                ->one();
        
        return $request;        
        
    }
    
    /*
     * Переключение статуса для заявки на платную услугу
     */
    public function switchStatus($status) {
        
        $this->status = $status;
        
        if ($status == StatusRequest::STATUS_CLOSE) {
            $this->date_closed = time();
        } else {
            $this->date_closed = null;
            $this->services_grade = null;
        }
        
        return $this->save() ? true : false;
        
    }
    
    public static function findByID($request_id) {
        return self::find()
                ->where(['services_id' => $request_id])
                ->asArray()
                ->one();
    }
    
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'services_id' => 'Services ID',
            'services_number' => 'Номер',
            'services_name_services_id' => 'Наименование услуги',
            'services_comment' => 'Текст заявки',
            'services_phone' => 'Ваш телефон',
            'created_at' => 'Дата заявки',
            'updated_at' => 'Дата закрытия',
            'status' => 'Статус',
            'services_dispatcher_id' => 'Диспетчер',
            'services_specialist_id' => 'Специалист',
            'services_account_id' => 'Лицевой счет',
        ];
    }
}
