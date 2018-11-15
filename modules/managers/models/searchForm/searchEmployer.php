<?php

    namespace app\modules\managers\models\searchForm;
    use yii\base\Model;

/**
 * Поиск по сотрудникам
 */
class searchEmployer extends Model {
    
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
                'pattern' => '/^[A-Za-zА-Яа-яЁё0-9\_\-\ ]+$/iu',
                'message' => 'Вы используете запрещенный набор символов',
            ],
        ];
    }
    
    /*
     * Поиск по диспетчерам
     */
    public function searshDispatcher($value) {
        
        $query = (new \yii\db\Query)
                ->select('e.employers_id as id, '
                        . 'e.employers_surname as surname, e.employers_name as name, e.employers_second_name as second_name,'
                        . 'u.user_login as login,'
                        . 'au.item_name as role')
                ->from('employers as e')
                ->join('LEFT JOIN', 'user as u', 'e.employers_id = u.user_employer_id')                
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ->where(['au.item_name' => 'dispatcher'])
                ->orderBy(['e.employers_surname' => SORT_ASC]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($value);
        $query->andFilterWhere(['like', 'e.employers_surname', $value])
                ->orFilterWhere(['like', 'e.employers_name', $value])
                ->orFilterWhere(['like', 'e.employers_second_name', $value])
                ->orFilterWhere(['like', 'u.user_login', $value]);
        
        return $dataProvider;
        
    }

    /*
     * Поиск по диспетчерам
     */
    public function searshSpecialist($value) {
        
        $query = (new \yii\db\Query)
                ->select('e.employers_id as id, '
                        . 'e.employers_surname as surname, e.employers_name as name, e.employers_second_name as second_name,'
                        . 'u.user_login as login,'
                        . 'au.item_name as role')
                ->from('employers as e')
                ->join('LEFT JOIN', 'user as u', 'e.employers_id = u.user_employer_id')                
                ->join('LEFT JOIN','auth_assignment as au','au.user_id = u.user_id')
                ->where(['au.item_name' => 'specialist'])
                ->orderBy(['e.employers_surname' => SORT_ASC]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($value);
        $query->andFilterWhere(['like', 'e.employers_surname', $value])
                ->orFilterWhere(['like', 'e.employers_name', $value])
                ->orFilterWhere(['like', 'e.employers_second_name', $value])
                ->orFilterWhere(['like', 'u.user_login', $value]);
        
        return $dataProvider;
        
    }
    
    public function attributeLabels() {
        return [
            '_input' => 'Поиск',
        ];
    }
    
}
