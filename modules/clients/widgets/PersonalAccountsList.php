<?php

    namespace app\modules\clients\widgets;
    use Yii;
    use yii\base\Widget;
    use app\models\PersonalAccount;

/**
 * Список лицевых счетов Собственника
 * dropDownList для NavBar
 */
class PersonalAccountsList extends Widget {
    
    public $_user;
    public $_list;

    public function init() {
        
        if (!$this->_user) {
            throw new \yii\base\InvalidConfigException('Отсутствует обязательный параметр $_user');
        }
        $this->_list = PersonalAccount::getListAccountByUserID($this->_user);
        
        parent::init();
        
    }

    public function run() {
        
        if (!$this->_list) {
            throw new \yii\base\InvalidConfigException('Ошибка при передечате параметра $_list');
        }
        return $this->render('personalaccountslist/list', ['account_list' => $this->_list]);
    }
    
}