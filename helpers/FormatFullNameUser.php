<?php

    namespace app\helpers;
    use yii\helpers\Html;
    use app\models\User;
    use app\models\Rents;
    use app\models\Clients;
    use app\models\Employers;

/**
 * Форматирование вывода полного имени пользователя 
 * Пользователь ищется по номеру телефона
 */
class FormatFullNameUser {
    
    public static function fullNameByPhone($phone) {

        $full_name = '';
        
        $client = Clients::find()
                ->where(['clients_mobile' => $phone])
                ->orWhere(['clients_phone' => $phone])
                ->asArray()
                ->one();

        $rent = Rents::find()
                ->where(['rents_mobile' => $phone])
                ->orWhere(['rents_mobile_more' => $phone])
                ->asArray()
                ->one();
        
        if ($client == null && $rent == null) {
            $full_name = 'Не задано';
        } elseif ($client != null && $rent == null) {
            $full_name = $client['clients_surname'] . ' ' . $client['clients_name'] . ' ' . $client['clients_second_name'];
        } elseif ($client == null && $rent != null) {
            $full_name = $rent['rents_surname'] . ' ' . $rent['rents_name'] . ' ' . $rent['rents_second_name'];
        }
        
        return $full_name;
        
    }
    
    /*
     * Формирование ссылки на профиль диспетчера/специалиста по ID сотрудника
     * @param integer $employer_id
     * @param boolean $disp Переключатель формирования ссылки на диспетчера (true),
     * @param boolean $disp Переключатель формирования ссылки на специалиста (false),
     * @param boolean $full Переключатель вывода ФИО сотрудника 
     *      true - Фамилия Имя Отчество
     *      false - Фамилия И. О.
     */
    public static function fullNameEmployer($employer_id, $disp = false, $full = false) {
        
        $employer = Employers::find()
                ->where(['employers_id' => $employer_id])
                ->asArray()
                ->one();
        
        $surname = $full ? $employer['employers_surname'] . ' ' : $employer['employers_surname'] . ' ';
        $name = $full ? $employer['employers_name'] . ' ' : mb_substr($employer['employers_name'], 0, 1, 'UTF-8') . '. ';
        $second_name = $full ? $employer['employers_second_name'] . ' ' : mb_substr($employer['employers_second_name'], 0, 1, 'UTF-8') . '.';
        
        $full_name = $surname . $name . $second_name;
        
        if ($disp == true) {
            $link = ['employers/edit-dispatcher', 'dispatcher_id' => $employer['employers_id']];
        } else {
            $link = ['employers/edit-specialist', 'specialist_id' => $employer['employers_id']];
        }
        
        return $employer ?
            Html::a($full_name, $link, ['target' => '_blank']) : 'Не назначен';
    }
    
    /*
     * Формирование ссылки на профиль сотрудника по ID пользователя
     * @param integer $user_id
     * Фамилия И. О.
     */
    public static function nameEmployerByUserID($user_id) {
        
        $user = (new \yii\db\Query)
                ->select('au.item_name as role, '
                        . 'e.employers_id as id, '
                        . 'e.employers_surname as surname, e.employers_name as name, e.employers_second_name as second_name, ')
                ->from('user as u')
                ->join('LEFT JOIN', 'employers as e', 'u.user_employer_id = e.employers_id')
                ->join('LEFT JOIN', 'auth_assignment as au', 'au.user_id = u.user_id')
                ->where(['u.user_id' => $user_id])
                ->one();

        $surname = $user['surname'] . ' ';;
        $name = mb_substr($user['name'], 0, 1, 'UTF-8') . '. ';
        $second_name = mb_substr($user['second_name'], 0, 1, 'UTF-8') . '.';
        $full_name = $surname . $name . $second_name;
        
        if ($user['role'] == 'administrator') {
            $link = ['managers/profile', 'managers' => $user['id']];
        } elseif ($user['role'] == 'dispatcher') {
            $link = ['employers/edit-dispatcher', 'dispatcher_id' => $user['id']];
        } elseif ($user['role'] == 'specialist') {
            $link = ['employers/edit-specialist', 'specialist_id' => $user['id']];
        }
        
        return $user ?
            Html::a($full_name, $link, ['target' => '_blank', 'class' => 'btn btn-link btn-xs']) : 'Не назначен';
        
    }    
    
}
