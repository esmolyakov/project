<?php

    namespace app\modules\managers\models;
    use app\models\Requests as BaseRequests;
    use app\models\StatusRequest;

/**
 *  Завяки
 */
class Requests extends BaseRequests {
    
    /*
     * Формируем запрос на получение всех заявок
     */
    public static function getAllRequests() {
        
        $requests = (new \yii\db\Query)
                ->select('r.requests_ident as number, '
                        . 'r.requests_comment as comment, '
                        . 'r.created_at as date_create, r.date_closed as date_close, '
                        . 'r.status as status, '
                        . 'tr.type_requests_name as category, '
                        . 'ed.employers_surname as surname_d, ed.employers_name as name_d, ed.employers_second_name as sname_d, '
                        . 'es.employers_surname as surname_s, es.employers_name as name_s, es.employers_second_name as sname_s, '
                        . 'he.estate_town as town, '
                        . 'h.houses_street as street, h.houses_number_house as house, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, '
                        . 'f.flats_number as flat')
                ->from('requests as r')
                ->join('LEFT JOIN', 'type_requests as tr', 'tr.type_requests_id = r.requests_type_id')
                ->join('LEFT JOIN', 'employers as ed', 'ed.employers_id = r.requests_dispatcher_id')
                ->join('LEFT JOIN', 'employers as es', 'es.employers_id = r.requests_specialist_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = r.requests_account_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_house_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'housing_estates as he', 'he.estate_id = h.houses_estate_name_id')                
                ->orderBy(['r.created_at' => SORT_DESC]);
        
        return $requests;
    }
    
    /*
     * Назначение диспетчера
     */
    public function chooseDispatcher($dispatcher_id) {
        
        $this->requests_dispatcher_id = $dispatcher_id;
        $this->status = StatusRequest::STATUS_IN_WORK;
        return $this->save(false) ? true : false;
        
    }

    /*
     * Назначение специалиста
     */
    public function chooseSpecialist($specialist_id) {
        
        $this->requests_specialist_id = $specialist_id;
        $this->status = StatusRequest::STATUS_PERFORM;
        return $this->save(false) ? true : false;
    }    
    
}
