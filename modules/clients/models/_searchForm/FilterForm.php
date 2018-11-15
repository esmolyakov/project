<?php
    
    namespace app\modules\clients\models\_searchForm;
    use yii\base\Model;
    use Yii;
    use app\models\Requests;
    use yii\data\ActiveDataProvider;

/**
 * Форма фильтрации для страницы Лицевой счет / Общая информация
 */
class FilterForm extends Model {
    
    public $_value;
    
    public function filerRequest($type_id) {
        
        $query = Requests::find()
                ->andWhere(['requests_type_id' => $type_id])
                ->all();
        return $query;
    }
    
    /*
     * Фильтр заявок 
     * @param integer $type_id по лицевому счету
     * @param integer $account_id по типу заявки 
     * @param integer $status по статусу
     */
    public function searchRequest($status) {
        
        // Получаем из сессии ID текущего личевого счета
        $account_id = 1;
        
        $query = Requests::find()
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        
        if ($status == -1) {
            // Статус = Все
            $value_query = $query;
        } elseif ($status !== -1) {
                // Статус = Выбор
                $value_query = $query->andWhere(['status' => $status]);
            }
            
        $this->load($account_id, $status);

        if (!$this->validate()) {
            return $query;
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $value_query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => Yii::$app->params['countRec']['client'] ? Yii::$app->params['countRec']['client'] : 15,
            ],
        ]);

        return $dataProvider;
        
    }
    
}
