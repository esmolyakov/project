<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Rubrics as ModelRubrics;

/**
 * Список рубрик новостной ленты
 */
class SubBarGeneralPage extends Widget {

    public $general_navbar = [
        'important_information' => 'Важная информация',
        'special_offers' => 'Специальные предложения',
        'house_news' => 'Новости дома',
    ];
    
    public function run() {
        
        return $this->render('rubrics/default', [
            'general_navbar' => $this->general_navbar,
        ]);
    }
        
}
