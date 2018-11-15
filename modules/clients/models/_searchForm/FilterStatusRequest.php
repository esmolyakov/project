<?php

    namespace app\modules\clients\models\_searchForm;
    use Yii;
    use app\models\Requests;

/**
 * Фильтр заявок по статусу
 */
class FilterStatusRequest extends Requests {
    
   /*
     * Фильтр заявок 
     * @param integer $account_id по типу заявки 
     * @param integer $status по статусу
     */
    public function searchRequest($status) {
        
        $account_id = Yii::$app->request->cookies->get('_userAccount')->value;
        
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
        
        $dataProvider = new \yii\data\ActiveDataProvider([
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
