<?php

    namespace app\modules\managers\models;
    use app\models\Services as BaseServices;

/**
 * Услуги и их стоимость
 */
class ServicesCost extends BaseServices {
    
    public static function getAllServices() {
        
        $list = (new \yii\db\Query)
                ->select('s.services_id as id, s.services_name as name, '
                        . 's.isType as type, '
                        . 's.services_cost as rate, '
                        . 'u.units_name as unit, '
                        . 'cs.category_name as category,')
                ->from('services as s')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = s.services_category_id')
                ->join('LEFT JOIN', 'units as u', 'u.units_id = s.services_unit_id')
                ->orderBy(['s.services_name' => SORT_ASC]);
        
        return $list;
        
    }
    
}
