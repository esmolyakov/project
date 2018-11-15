<?php

    namespace app\modules\clients\models\_searchForm;
    use Yii;
    use yii\base\Model;

/*
 * Поиск по специалисту/исполнителю
 */
class searchInPaidServices extends Model {
    
    public $_input;

    /*
     * Правила валидации
     */
    public function rules() {
        
        return [
            ['_input', 'filter', 'filter' => 'trim'],
            /* ['_input', 'string', 'min' => 3, 'max' => 70],
            ['_input', 
                'match',
                'pattern' => '/^[А-Яа-яёЁ\s,]+$/u',
                'message' => 'Данное поле может содержать только буквы русского алфавита',
            ],
             */
        ];
        
    }
    
    /*
     * Поиск по исполнителю
     */
    public function search($value) {
        
        $account = Yii::$app->request->cookies->get('_userAccount')->value;
                
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
                ->andWhere(['services_account_id' => $account])
                ->orderBy(['created_at' => SORT_DESC]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($value, $account);
        
        $query->andFilterWhere(['like', 'p.services_specialist_id', $value]);
        
        return $dataProvider;
        
    }
    
    /*
     * Метки для полей формы
     */
    public function attributeLabels() {
        return [
            '_input' => 'Фамилия исполнителя',
        ];
    }
    
}
