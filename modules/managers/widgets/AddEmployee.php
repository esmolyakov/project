<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;
    use app\modules\managers\models\Dispatchers;
    use app\modules\managers\models\Specialists;

/**
 * Назначение диспетчера в заявке
 */
class AddEmployee extends Widget {
    
    public $dispatcher_list = [];
    public $specialist_list = [];

    public function init() {
        
        $this->dispatcher_list = Dispatchers::getListDispatchers()->all();
        $this->specialist_list = Specialists::getListSpecialists()->all();
        
        if ($this->dispatcher_list == null || $this->specialist_list == null) {
            throw new \yii\base\InvalidConfigException('Что-то пошло не так');
        }
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('addemployee/employee_list', [
            'dispatcher_list' => $this->dispatcher_list,
            'specialist_list' => $this->specialist_list,
        ]);
        
    }
    
}
