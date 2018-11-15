<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Voting;
    use app\models\RegistrationInVoting;

/**
 * Голосование
 */
class VotingController extends AppClientsController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        $estate_id = Yii::$app->userProfile->_user['estate_id'];
        $house_id = Yii::$app->userProfile->_user['house_id'];
        $flat_id = Yii::$app->userProfile->_user['flat_id'];        
        
        $voting_list = Voting::findAllVotingForClient($estate_id, $house_id, $flat_id);
        
        return $this->render('index', [
            'voting_list' => $voting_list,
        ]);
        
    }
    
    /*
     * Страница конечного голосования
     */
    public function actionViewVoting($voting_id) {
        
        /*
         *  Проверем наличие куки на загрузку модального окна ввода СМС кода, 
         *  если кука существует, то загружаем модальное окно сразу при загрузке страницы с голосованием
         */
        $modal_show = $this->getCookieVoting($voting_id) ? true : false;
//        var_dump($modal_show);
//        die();
        
        $voting = Voting::findVotingById($voting_id);
        if ($voting === null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model = new \app\modules\clients\models\form\CheckSMSVotingForm();
        
        $is_register = RegistrationInVoting::find()
                ->andWhere(['voting_id' => $voting_id, 'user_id' => Yii::$app->user->identity->id])
                ->asArray()
                ->one();
        
        return $this->render('view-voting', [
            'voting' => $voting,
            'is_register' => $is_register,
            'model' => $model,
            'modal_show' => $modal_show,
        ]);
        
    }
    
    /*
     * Регистрация на участие в голосовании
     * 
     * При нажатии на кнопку "Принять участние" создаем запись в таблице "Участники"
     * Генерируем случайное число, которое будем отправлять в СМС пользователлю
     * Формируем время ожидания ввода сгенерированного числа
     * Если время истекает, запись из бд о регистрации на участие в голосованиии удаляется
     * 
     * Если регистрация на участие прошла успешно, записываем куку модального окна ввода СМС кода
     */
    public function actionParticipateInVoting() {
        
        $voting_id = Yii::$app->request->post('voting');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $register = new RegistrationInVoting();
            if ($register->registerIn($voting_id)) {
                $this->setCookieVoting($voting_id);
                return ['success' => true, 'voting_id' => $voting_id];
            }
            return ['success' => false];
        }
        return ['success' => false];
        
    }
        
    /*
     * Отказ от участия в голосовании
     */
    public function actionRenouncementToParticipate() {
        
        $voting_id = Yii::$app->request->post('votingId');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (RegistrationInVoting::deleteRegisterById($voting_id)) {
                $this->deleteCookieVoting($voting_id);
            }
            return ['success' => true];
        }
        return ['success' => false];
        
    }
    
    /*
     * Повторная отправка вновь сгенерированного кода
     */
    public function actionRepeatSmsCode() {
        
        $voting_id = Yii::$app->request->post('votingId');
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $register = RegistrationInVoting::findById($voting_id);
            if (!$register->generateNewSMSCode()) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        return ['success' => false];
                
    }
    
    /*
     * Метод записи куки
     */
    public function setCookieVoting($voting_id) {
        
        $cookies = Yii::$app->response->cookies;
        $name_modal = '_participateInVoting-' . $voting_id;
      
        // Количество минут для хранения куки
        $minutes_to_add = 10;

        $cookies->add(new \yii\web\Cookie([
            'name' => $name_modal,
            'value' => $voting_id,
            'expire' => strtotime("+ {$minutes_to_add} minutes"),
        ]));
      
    }
    
    /*
     * Получение куки 
     * 
     * Если заданной куки не существует, удаляем запись регистрации участия в голосовании
     */
    private function getCookieVoting($voting_id) {
        
        $cookies = Yii::$app->request->cookies;
        $name_modal = '_participateInVoting-' . $voting_id;
        
        if (!$cookies->has($name_modal)) {
            $register = RegistrationInVoting::deleteRegisterById($voting_id);
            return false;
        } 
        return true;
    }
    
    /*
     * Удаление куки
     * В случае отказа от участия в голосовании удаляем куку
     */
    private function deleteCookieVoting($voting_id) {
        $cookies = Yii::$app->response->cookies;
        $name_modal = '_participateInVoting-' . $voting_id;
        return $cookies->remove($name_modal) ? true : false;
        
    }    
    
}
