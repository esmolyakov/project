<?php

    namespace app\modules\managers\models\searchForm;
    use yii\base\Model;

/**
 * Поиск по услуг
 */
class searchService extends Model {
    
    public $_input;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            ['_input', 'string', 'min' => 1, 'max' => '70'],
            ['_input', 'filter', 'filter' => 'trim'],
            ['_input', 
                'match',
                'pattern' => '/^[А-Яа-яЁё0-9\ \-\(\)]+$/iu',
                'message' => 'Вы используете запрещенный набор символов',
            ],
        ];
    }
    
    /*
     * Поиск по диспетчерам
     */
    public function searshService($value) {
        
        $query = (new \yii\db\Query)
                ->select('s.services_id as id, s.services_name as name, '
                        . 's.isType as type, '
                        . 's.services_cost as rate, '
                        . 'u.units_name as unit, '
                        . 'cs.category_name as category,')
                ->from('services as s')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = s.services_category_id')
                ->join('LEFT JOIN', 'units as u', 'u.units_id = s.services_unit_id')
                ->orderBy(['s.services_name' => $value]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($value);
        $query->andFilterWhere(['like', 's.services_name', $value]);
        
        return $dataProvider;
        
    }
     
    public function attributeLabels() {
        return [
            '_input' => 'Поиск',
        ];
    }
    
}
