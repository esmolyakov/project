<?php

    namespace app\modules\managers\models\form;
    use app\models\Voting;
    use app\models\Questions;
    use Yii;
    use yii\base\Model;
    use yii\widgets\ActiveForm;

/*
 * Голосование
 */    
class VotingForm extends Model
{
    private $_voting;
    private $_questions;
    
    public $imageFile;

    public function rules()
    {
        return [
            [['Voting'], 'required'],
            [['Questions'], 'required'],
            [['imageFile'], 'image',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg',
                'maxSize' => 256 * 1024,
                'mimeTypes' => 'image/*',
            ],
        ];
    }

    /*
     * Валидация всех моделей
     */
    public function afterValidate() {
        if (!Model::validateMultiple($this->getAllModels())) {
            $this->addError(null); 
        }
        parent::afterValidate();
    }

    /*
     * Метод сохранения записи
     */
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        if (!$this->voting->save()) {
            $transaction->rollBack();
            return false;
        }
        
        if (!$this->saveQuestions()) {
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }
    
    /*
     * Сохранение набора вопросов для голосования
     */
    public function saveQuestions() {
        
        $keep = [];
        foreach ($this->questions as $question) {
            $question->questions_voting_id = $this->voting->voting_id;
            $question->questions_user_id = Yii::$app->user->identity->id;
            if (!$question->save(false)) {
                return false;
            }
            $keep[] = $question->questions_id;
        }
        $query = Questions::find()->andWhere(['questions_voting_id' => $this->voting->voting_id]);
        if ($keep) {
            $query->andWhere(['not in', 'questions_id', $keep]);
        }
        foreach ($query->all() as $question) {
            $question->delete();
        }        
        return true;
    }

    public function getVoting() {
        return $this->_voting;
    }

    public function setVoting($voting) {
        if ($voting instanceof Voting) {
            $this->_voting = $voting;
        } else if (is_array($voting)) {
            $this->_voting->setAttributes($voting);
        }
    }

    public function getQuestions() {
        if ($this->_questions === null) {
            $this->_questions = $this->voting->isNewRecord ? [] : $this->voting->question;
        }
        return $this->_questions;
    }

    private function getQuestion($key) {
        $question = $key && strpos($key, 'new') === false ? Questions::findOne($key) : false;
        if (!$question) {
            $question= new Questions();
            $question->loadDefaultValues();
        }
        return $question;
    }

    public function setQuestions($questions) {
        unset($questions['__id__']);
        $this->_questions = [];
        foreach ($questions as $key => $question) {
            if (is_array($question)) {
                $this->_questions[$key] = $this->getQuestion($key);
                $this->_questions[$key]->setAttributes($question);
            } elseif ($question instanceof Questions) {
                $this->_questions[$question->id] = $question;
            }
        }
    }

    /*
     * Вывод ошибок заполнения формы
     */
    public function errorSummary($form) {
        
        $errorLists = [];
        foreach ($this->getAllModels() as $id => $model) {
            $errorList = $form->errorSummary($model, [
              'header' => '<p>Пожалуйста, исправьте ошибки в заполнении формы <b>' . $id . '</b></p>',
            ]);
            $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error
            $errorLists[] = $errorList;
        }
        return implode('', $errorLists);
    }
    
    /*
     * Загрузка обложки голосования
     */
    public function upload() {
        
        // Перед загрузкой изображения, получаем путь на текущее изображение
        $current_image = $this->voting->voting_image;
        
        if ($this->validate()) {
            // Если перед сохранением файл выбран
            if (!empty($this->imageFile)) {
                $dir = Yii::getAlias('upload/voting/cover/');
                $file_name = 'previews_voting_' . time() . '.' . $this->imageFile->extension;
                // Формируем путь нового изображения для хранения в БД
                $path = '/' . $dir . $file_name;
                // Сохраняем новое изображаение
                $this->imageFile->saveAs($dir . $file_name);
                // Удаляем старое
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                // Если не было выбрано новое изображение, то путь к изображению в БД оставляем текущий
                $path = $current_image;
            }
            return $path;
        } else {
            return false;
        }
    }

    /*
     * Формируем массив моделей
     */
    private function getAllModels() {
        $models = [
            'Голосование' => $this->voting,
        ];
        foreach ($this->questions as $id => $question) {
            $models['Вопросы, вопрос ' . $id] = $this->questions[$id];
        }
        return $models;
    }
}