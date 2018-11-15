<?php

    namespace app\modules\managers\models;
    use yii\helpers\ArrayHelper;
    use app\helpers\FormatHelpers;
    use app\models\Houses;
//    use app\models\News;
    use app\models\HousingEstates;

/**
 * Дома
 * Жилой комплекс
 */
class HousesEstates extends Houses {
    
    /*
     * В зависимости пришедщего статуса публикации формируем список жилых комплексов или домов
     * # Новости
     */
    public static function getHouseOrEstate($status) {
        
        $null_list = ['0' => 'Для всех'];
        
        $_house = Houses::find()->asArray()->all();
        $current_house = ArrayHelper::map(
                $_house, 
                'houses_id', 
                function ($array) {
                    return FormatHelpers::formatFullAdress($array['houses_town'], $array['houses_street'], $array['houses_number_house'], false, false);
                }
        );
        
        
        $_estates = HousingEstates::find()->asArray()->all();
        $housing_estates = ArrayHelper::map($_estates, 'estate_id', 'estate_name');
        
        if ($status === News::FOR_ALL) {
            return $null_list;
        } elseif ($status === News::FOR_ALL_HOUSE_AREA) {
            return $housing_estates;
        } elseif ($status === News::FOR_CURRENT_HOUSE) {
            return $current_house;
        }
    }
    
}
