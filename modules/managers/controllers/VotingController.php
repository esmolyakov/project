<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\Pagination;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Voting;
    use app\models\Houses;
    use app\helpers\FormatHelpers;
    use app\modules\managers\models\form\VotingForm;

/**
 *  Голосование
 */
class VotingController extends AppManagersController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        // Получаем все доступные голосования
        $query = Voting::findAllVoting();
        $count_voting = clone $query;
        $pages = new Pagination([
            'totalCount' => $count_voting->count(),
            'pageSize' => 15,
            'defaultPageSize' => 15,
        ]);
        
        $view_all_voting = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        return $this->render('index', [
            'view_all_voting' => $view_all_voting,
            'pages' => $pages,
        ]);
        
    }
    
    /*
     * Создание голосования
     * 
     * @param array $type_voting Тип голосования (Весь дом, конкретный подъезд)
     */
    public function actionCreate() {
        
        $model = new VotingForm();
        $model->voting = new Voting();
        $model->setAttributes(Yii::$app->request->post());
        
        $type_voting = Voting::getTypeVoting();
        
        if (Yii::$app->request->post() && $model->validate()) {
            // Приводим дату завершения, дату окончания к формату бд
//            $model->voting->voting_date_start = Yii::$app->formatter->asDatetime($model->voting->voting_date_start, 'php:Y-m-d H:i:s');
//            $model->voting->voting_date_end = Yii::$app->formatter->asDatetime($model->voting->voting_date_end, 'php:Y-m-d H:i:s');
            // Получаем загружаемую оложку для голосования
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Вызываем метод на загрузку обложки, при успехе - получаем полный путь к загруженной обложке
            $path = $model->upload();
            if ($model->imageFile && $path) {
                $model->voting->voting_image = $path;
            }
            // Сбрасываем путь загруженного изображения
            $model->imageFile = null; 
            // Сохраняем модель
            if ($model->save()) {
                Yii::$app->session->setFlash('voting-admin', [
                    'success' => true,
                    'message' => 'Новое голосование было успешно создано',
                ]);
                return $this->redirect(['view', 'voting' => $model->voting->voting_id]);
            } else {
                Yii::$app->session->setFlash('voting-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
                
        return $this->render('create', [
            'model' => $model,
            'type_voting' => $type_voting]);
    }
    
    /*
     * Просмотр страницы голосования
     */
    public function actionView($voting) {
        
        $model = new VotingForm();
        $model->voting = Voting::findOne($voting);
        
        if ($model->voting == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model->setAttributes(Yii::$app->request->post());
        
        $type_voting = Voting::getTypeVoting();        
        
        if (Yii::$app->request->post()) {
            
            // Получаем загружаемую оложку для голосования
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // Вызываем метод на загрузку обложки, при успехе - получаем полный путь к загруженной обложке
            $path = $model->upload();
            
            if ($model->imageFile && $path) {
                $model->voting->voting_image = $path;
            }
            $model->imageFile = null;
            if (!$model->save()) {
                Yii::$app->session->setFlash('voting-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('voting-admin', [
                'success' => true,
                'message' => 'Изменения были успешно сохранены',
            ]);
            return $this->redirect(['view', 'voting' => $model->voting->voting_id]);
        }
        return $this->render('view', [
            'model' => $model,
            'type_voting' => $type_voting]);
    }
    
    /*
     * Зависимый переключатель типа голосования
     *      Для конкретного дома
     *      Для конкретного подъезда
     */
    public function actionForWhomVoting($status) {
        
        $houses = Houses::getHousesList();
        
        if ($status == 0) {
            foreach ($houses as $house) {
                $name = FormatHelpers::formatFullAdress($house['estate_town'], $house['houses_street'], $house['houses_number_house']);
                echo '<option value="' . $house['houses_id'] . '">' . $name . '</option>';
            }            
        } elseif ($status == 1) {
            foreach ($houses as $house) {
                $name = FormatHelpers::formatFullAdress($house['estate_town'], $house['houses_street'], $house['houses_number_house'], $house['flats_porch']);
                return '<option value="' . $house['houses_id'] . '">' . $name . '</option>';
            }            
        }
        
    }
    
    /*
     * Запрос на удаление голосования
     */
    public function actionConfirmDeleteVoting() {
        
        $voting_id = Yii::$app->request->post('votingId');
        
        if (Yii::$app->request->isAjax) {
            $voting = Voting::findByID($voting_id);
            if (!$voting->delete()) {
                Yii::$app->session->setFlash('voting-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('voting-admin', [
                    'success' => true,
                    'message' => 'Голосование ' . $voting->voting_title . ' было успешно удалено',
            ]);
            return $this->redirect(['index']);
        }
        return ['success' => false];
    }
    
    /*
     * Запрос на завешение голосования
     */
    public function actionConfirmCloseVoting() {
        
        $voting_id = Yii::$app->request->post('votingId');
        $current_time = strtotime(date('Y-m-d'));        
        
        if (Yii::$app->request->isAjax) {
            $voting = Voting::findByID($voting_id);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($current_time < strtotime($voting->voting_date_end)) {
                return [
                    'success' => true, 
                    'close' => 'ask', 
                    'message' => 'Дата завершения голосования отличается от текущей, все равно завершить голосование',
                    'title' => $voting->voting_title];
            } else {
                return [
                    'success' => true, 
                    'close' => 'yes', 
                    'message' => 'Вы действительно хотите завершить голосование ',
                    'title' => $voting->voting_title];
            }
        }
        return ['success' => false];
    }
    
    public function actionCloseVoting() {
        
        $voting_id = Yii::$app->request->post('votingId');
        if (Yii::$app->request->isAjax) {
            $voting = Voting::findByID($voting_id);
            if (!$voting->closeVoting()){
                Yii::$app->session->setFlash('voting-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('voting-admin', [
                    'success' => true,
                    'message' => 'Статус голосования ' . $voting->voting_title . ' изменился на "Завершено"',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
    }
    
}
