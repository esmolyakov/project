<?php

    namespace app\modules\managers\models;
    use yii\helpers\ArrayHelper;
    use app\models\Posts as BasePost;

/**
 * Должности
 * 
 * Наследует основной класс Должности
 */
class Posts extends BasePost {
    
    public static function getPostList($departmantId) {
        
        $list = self::find()
                ->where(['posts_department_id' => $departmantId])
                ->asArray()
                ->all();
        
        return ArrayHelper::map($list, 'posts_id', 'posts_name');
        
    }
    
}
