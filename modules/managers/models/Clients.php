<?php

    namespace app\modules\managers\models;
    use app\models\Clients as BaseClients;

/*
 * Клиенты
 * 
 * Наследуется от основной модели Клиенты
 */
class Clients extends BaseClients {
    
    public static function getListClients() {
        
        $query = (new \yii\db\Query)
                ->select('c.clients_id as client_id, '
                        . 'c.clients_name as name, c.clients_second_name as second_name, c.clients_surname as surname, '
                        . 'a.account_number as number, a.account_balance as balance, '
                        . 'u.status as status, '
                        . 'he.estate_town as town, '
                        . 'h.houses_street as street, h.houses_number_house as house, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, '
                        . 'f.flats_number as flat')
                ->from('clients as c')
                ->join('LEFT JOIN', 'user as u', 'u.user_client_id = c.clients_id')
                ->join('LEFT JOIN', 'personal_account as a', 'a.personal_clients_id = c.clients_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_account_id = a.account_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'housing_estates as he', 'he.estate_id = h.houses_estate_name_id')
                ->orderBy('c.clients_surname')
                ->groupBy('a.account_number');
        
        return $query;
        
    }
    
}
