<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Саб меню для раздела профиль пользователя
 *      Профиль
 *      Настройки
 *      История
 */
    
class SubMenuProfile extends Widget {
    
    public $_items = [
        'index' => 'Профиль',
        'settings-profile' => 'Настройки',
        'history' => 'История',
    ];


    public function run() {
        
        return $this->render('submenuprofile/submenu', ['items' => $this->_items]);
        
    }
    
}
