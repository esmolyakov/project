<?php

    namespace app\modules\managers\models;
    use app\models\Employers;
//    use app\models\PaidServices;
    use app\models\StatusRequest;

/**
 * Специалисты
 */
class Specialists extends Employers {
    
    public static function getListSpecialists() {
        
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
        
        return $query;
        
    }
    
    /*
     * Поиск не закрытых:
     *      заявок 
     *      заявок за платные услуги
     * 
     * true Имеются не закрытые заявки
     * false Все заявки закрыты
     */
    public static function findRequestsNotClose($specialists_id) {
        
        $requests = Requests::find()
                ->andWhere(['requests_specialist_id' => $specialists_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->count();
        
        $paid_services = PaidServices::find()
                ->andWhere(['services_specialist_id' => $specialists_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->count();
        
        if (($requests + $paid_services) > 0) {
            return true;
        }
        
        return false;
        
    }
    
}
