<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\helpers\ArrayHelper;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Houses;
    use app\models\Image;
    use app\models\CharacteristicsHouse;
    use app\models\Flats;
    use app\models\NotesFlat;
    use app\models\HousingEstates;
    use app\modules\managers\models\form\HouseForm;

/**
 * Жилищный фонд
 */
class EstatesController extends AppManagersController {
    
    /*
     * Жилищный фонд, главная страница
     */
    public function actionIndex() {
        
        // Из куки получаем выбранный дом
        $house_cookie = $this->actionReadCookies();
        
        $houses_list = Houses::getAllHouses();
        $characteristics = $house_cookie ? CharacteristicsHouse::getCharacteristicsByHouse($house_cookie) : null;
        $flats = $house_cookie ? Flats::getFlatsByHouse($house_cookie) : null;
        $files = $house_cookie ? Image::getAllFilesByHouse($house_cookie, 'Houses') : null;
        
        return $this->render('index', [
            'houses_list' => $houses_list,
            'characteristics' => $characteristics,
            'flats' => $flats,
            'files' => $files,
            'house_cookie' => $house_cookie,
        ]);
        
    }
    
    /*
     * Создание нового жилого объекта ЖК + Дом
     */
    public function actionCreateHouse() {
        
        $estates_list = ArrayHelper::map(HousingEstates::getHousingEstateList(), 'estate_id', 'estate_name');
        
        $model = new HouseForm();
        $model->houses = new Houses;
        $model->houses->loadDefaultValues();
        $model->setAttributes(Yii::$app->request->post());
        
        if (Yii::$app->request->post() && $model->validate()) {
            // Получаем загружаемые документа
            $files = UploadedFile::getInstances($model->houses, 'upload_files');
            $model->houses->upload_files = $files;
            // Сохраняем
            if ($model->save()) {
                // Загружаем прикрепленне файлы
                $model->houses->uploadFiles($files);
                Yii::$app->session->setFlash('estate-admin', [
                    'success' => true,
                    'message' => 'Запись успешно создана',
                ]);
                return $this->redirect(['view-house', 'house_id' => $model->houses->houses_id]);
            } else {
                Yii::$app->session->setFlash('estate-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);                
            }
        }
        
        return $this->render('create-house', [
            'model' => $model,
            'estates_list' => $estates_list,
        ]);
        
    }
    
    /*
     * Просмотр и редактирование записи жилой дом
     */
    public function actionViewHouse($house_id) {
        
        $model = new HouseForm();
        $model->houses = Houses::findHouseById($house_id);
        
        if ($model->houses === null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model->setAttributes(Yii::$app->request->post());

        $estates_list = ArrayHelper::map(HousingEstates::getHousingEstateList(), 'estate_id', 'estate_name');
        // Получаем прикрепленные к заявке файлы
        $upload_files = Image::getAllDocByNews($model->houses->houses_id, $model_name = 'Houses');
        
        if (Yii::$app->request->post()) {
            $files = UploadedFile::getInstances($model->houses, 'upload_files');
            $model->houses->upload_files = $files;
            $model->houses->uploadFiles($files);
            $model->houses->upload_files = null;
            if (!$model->save()) {
                Yii::$app->session->setFlash('estate-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            Yii::$app->session->setFlash('estate-admin', [
                'success' => true,
                'message' => 'Изменения были успешно обновлены',
            ]);
            return $this->redirect(['view-house', 'house_id' => $model->houses->houses_id]);
            
        }
        
        return $this->render('view-house', [
            'model' => $model,
            'estates_list' => $estates_list,
            'upload_files' => $upload_files,
        ]);        
        
    }
    
    public function actionCreateFlat() {
        
        return $this->render('create-flat');
        
    }
    
    /*
     * Загрузка модального окна на редактирование описания дома
     * Сохранение данных
     */
    public function actionUpdateDescription($house_id) {
        
        $model = Houses::findOne($house_id);
        $model->scenario = Houses::SCENARIO_EDIT_DESCRIPRION;
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form/edit_description', [
                'model' => $model]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
    }
    
    /*
     * Загрузка модального окна на добаление характеристики
     * Сохранение данных
     */
    public function actionCreateCharacteristic() {
        
        $house_cookie = $this->actionReadCookies();
        if ($house_cookie === null) {
            Yii::$app->session->setFlash('estate-admin', [
                'success' => false,
                'error' => 'Для добавления характеристики, пожалуйста, выберите дом из списка "Жилой комплекс" слева',
            ]);
            return $this->redirect(['index']);
        }
        
        $model = new CharacteristicsHouse();
        $model->scenario = CharacteristicsHouse::SCENARIO_ADD_CHARACTERISTIC;
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form/add_characteristic', [
                'model' => $model,
                'house_id' => $house_cookie,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
    }
    
    /*
     * Вывод модального окна для загрузки документов
     * Сохранение данных
     */
    public function actionLoadFiles() {
        
        $house_cookie = $this->actionReadCookies();
        if ($house_cookie === null) {
            Yii::$app->session->setFlash('estate-admin', [
                'success' => false,
                'error' => 'Для загрузки документа, пожалуйста, выберите дом из списка "Жилой комплекс" слева',
            ]);
            return $this->redirect(['index']);
        }
        
        $model = Houses::findOne($house_cookie);
        $model->scenario = Houses::SCENARIO_LOAD_FILE;
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form/load_files', [
                'model' => $model,
                'house_cookie' => $house_cookie,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'upload_file');
            if ($model->uploadFile($file)) {
                return $this->redirect(['index']);
            }
        }
        return 'При загрузке фала возникла ошибка';
    }
    
    /*
     * Загрузка модального окна с формой на добавление примечания к квартире
     */
    public function actionCheckStatusFlat($flat_id) {
        
        $model = new NotesFlat();
        $model->scenario = NotesFlat::SCENARIO_ADD_NOTE;
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form/check_status_flat', [
                'flat_id' => $flat_id,
                'model' => $model,
            ]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
    }
    
    /*
     * Валидация форм
     *      Редактирование описание дома
     *      Добавление характеристики
     *      Добавление примечания
     */
    public function actionEditDescriptionValidate($form) {
        
        switch ($form) {
            
            case 'edit-form-description':
                $model = new Houses();
                break;
            case 'add-characteristic':
                $model = new CharacteristicsHouse();
                break;
            case 'form-add-note':
                $model = new NotesFlat();
                break;
            default:
                return $this->goHome();
                
        }
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
    }
    
    /*
     * Загрузка характеристики дома
     * Квартиры
     * Прикрепленные документы
     */
    public function actionViewCharacteristicHouse() {
        
        $house_id = Yii::$app->request->post('house');
        $this->setCookieChooseHouse($house_id);
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $characteristics = CharacteristicsHouse::getCharacteristicsByHouse($house_id);
            $flats = Flats::getFlatsByHouse($house_id);
            $files = Image::getAllFilesByHouse($house_id, 'Houses');
            
            $data_characteristics = $this->renderPartial('data/characteristics_house', ['characteristics' => $characteristics]);
            $data_flats = $this->renderPartial('data/view_flats', ['flats' => $flats]);
            $data_files = $this->renderPartial('data/view_upload_files', ['files' => $files]);
            
            return ['data' => $data_characteristics, 'flats' => $data_flats, 'files' => $data_files];
        }
    }
    
    /*
     * Удаление выбранной характеристики
     */
    public function actionDeleteCharacteristic(){
        $characteristic_id = Yii::$app->request->post('charId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $characteristic = CharacteristicsHouse::findOne($characteristic_id);
            if (!$characteristic->delete()) {
                return ['success' => false];
            } else {
                return ['success' => true];
            }
        }
        return ['success' => false];
    }
    
    /*
     * Удаление выбранного документа
     */
    public function actionDeleteFilesHouse() {
        $file_id = Yii::$app->request->post('fileId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $file = Image::findById($file_id);
            if (!$file->delete()) {
                return ['success' => false];
            } else {
                return ['success' => true];
            }
        }
        return ['success' => false];        
    }
    
    /*
     * Удаление выбранного примечания
     */
    public function actionDeleteNoteFlat() {
        
        $note_id = Yii::$app->request->post('noteId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $note = NotesFlat::findOne($note_id);
            $note->delete();
            return ['success' => true, 'note_id' => $note];
        }
        
    }
    
    /*
     * Снять статус "Должник" с квартиры
     */
    public function actionTakeOffStatusDebtor() {
        
        $flat_id = Yii::$app->request->post('flatId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $flat = Flats::findById($flat_id);
            if (!$flat->takeOffStatus()) {
                return ['success' => false];
            }
            return ['success' => true];
        }
        
    }
    
    /*
     * Скачивание запрашиваемого файла с сервера
     */
    public function actionDownloadFilesHouse() {
        
        $file_id = Yii::$app->request->post('fileId');
        $file = Image::findById($file_id);
        $path = 'upload/store/' . $file->filePath;
        
        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        } else {
            throw new \yii\web\NotFoundHttpException('Такого файла не существует ');
        }
        
    }
    
    /*
     * Установка куки выбранного дома
     */
    public function setCookieChooseHouse($value) {
        
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie ([
            'name' => 'choosingHouse',
            'value' => $value,
            'expire' => time() + 60*60*24*7,
        ]));
    }
    
}
