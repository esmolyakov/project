<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Answers;
    use app\models\Voting;

class Questions extends ActiveRecord
{
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['questions_text', 'required'],
            ['questions_text', 'filter', 'filter' => 'trim'],
            [['questions_voting_id', 'questions_user_id'], 'integer'],
            [['questions_text'], 'string', 'min' => 10, 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['questions_voting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voting::className(), 'targetAttribute' => ['questions_voting_id' => 'voting_id']],
        ];
    }

    /**
     * Связь с таблицей Ответы
     */
    public function getAnswer() {
        return $this->hasMany(Answers::className(), ['answers_questions_id' => 'questions_id']);
    }

    /**
     * Свзяь с таблицей Голсование
     */
    public function getVoting() {
        return $this->hasOne(Voting::className(), ['voting_id' => 'questions_voting_id']);
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'questions_id' => 'Questions ID',
            'questions_voting_id' => 'Questions Voting ID',
            'questions_text' => 'Текст вопроса',
            'questions_user_id' => 'Пользователь',
            'created_at' => 'Дата создания вопроса',
            'updated_at' => 'Дата одновления вопроса',
        ];
    }

}
