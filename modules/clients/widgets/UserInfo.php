<?php

    namespace app\modules\clients\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;

/**
 * Профиль пользователя из навигационного меню
 */
class UserInfo extends Widget {
    
    public $_user;
    public $_value_choosing;

    public function init() {
        
        if ($this->_value_choosing == null) {
            throw new InvalidConfigException('Ошибка передачи параметров');
        }
        
        parent::init();        
        
    }

    public function run() {
        
        return $this->render('userinfo/profile', [
            'user_info' => $this->_user, 
            'account_number' => $this->_value_choosing,
        ]);
        
    }
    
}
