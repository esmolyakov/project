<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\Services;

/**
 * Новая услуга
 */
class ServiceForm extends Model {

    public $service_name;
    public $service_category;
    public $service_cost;
    public $service_unit;
    public $service_type;
    public $service_description;
    public $service_image;
    
    public function rules() {
        return [
            [[
                'service_name', 'service_category', 'service_type',
                'service_cost', 'service_unit'], 'required'],
            
            [['service_name', 'service_description'], 'string', 'min' => 3, 'max' => 255],
            ['service_name', 
                'match', 
                'pattern' => '/^[А-Яа-яЁё0-9\ \-\(\)]+$/iu'],
            
            ['service_cost', 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'], 
            ['service_cost', 'validateCost'],
            
            [['service_image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['service_image'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
        ];
    }
    
    /*
     * Валидация поля Тариф
     * Тариф не может быть равен нулю и быть отрицательным
     */
    public function validateCost() {
        if ($this->service_cost <= 0) {
            $this->addError('service_cost', 'Значение тарифа не может быть отрицательным или равным нулю');
        }
    }
    
    public function save($file) {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $service = new Services();
            $service->services_category_id = $this->service_category;            
            $service->services_name = $this->service_name;
            $service->services_unit_id = $this->service_unit;
            $service->services_cost = $this->service_cost;
            $service->services_description = $this->service_description;
            $service->isType = $this->service_type;
            $service->uploadImage($file);
            
            if (!$service->save()) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    // 'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                    'error' => $service->getFirstErrors(),
                ]);
                return false;
            }
            
            $transaction->commit();
            return $service->services_id;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
        
    }
    
    public function attributeLabels() {
        return [
            'service_name' => 'Наименование услуги',
            'service_category' => 'Вид услуги',
            'service_cost' => 'Стоимость',
            'service_unit' => 'Единица измерения',
            'service_type' => 'Тип услуги',
            'service_description' => 'Описание',
            'service_image' => 'Изображение',
        ];
    }
    
}
