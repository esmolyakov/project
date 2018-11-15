<?php

    namespace app\behaviors;
    use yii\base\Behavior;
    use yii\web\User;

/*
 * Заполнение даты последнего логина пользователя
 */    
class LoginTimestampBehavior extends Behavior
{
    public $attribute = 'last_login';


    public function events()
    {
        return [
            User::EVENT_AFTER_LOGIN => 'afterLogin'
        ];
    }

    public function afterLogin($event)
    {
        $user = $event->identity;
        $user->touch($this->attribute);
        $user->save(false);
    }
}
