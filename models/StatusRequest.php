<?php

    namespace app\models;

/**
 * Статусы заявок
 * Бесплатные и платные услуги
 */
class StatusRequest {
    
    const STATUS_NEW = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_PERFORM = 2;
    const STATUS_FEEDBAK = 3;
    const STATUS_CLOSE = 4;
    const STATUS_REJECT = 5;
    const STATUS_CONFIRM = 6;
    const STATUS_ON_VIEW = 7;
    
    /*
     * Массив статусов заявок
     */
    public static function getStatusNameArray() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBAK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
            self::STATUS_REJECT => 'Отклонена',
            self::STATUS_CONFIRM => 'Подтверждена пользователем',
            self::STATUS_ON_VIEW => 'На рассмотрении',
        ];
    }
    
    /*
     * Массив статусов заявок для пользователя
     */
    public static function getUserStatusRequests() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBAK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
        ];       
    }    
    
    /*
     * Получить название статуса по его номеру
     */
    public static function statusName($status) {
        return ArrayHelper::getValue(self::getStatusNameArray(), $status);
    }
    
}
