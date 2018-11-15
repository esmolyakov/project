<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Requests;
    use yii\behaviors\TimestampBehavior;

/**
 * Комментарии к заявкам
 */
class CommentsToRequest extends ActiveRecord
{
    const SCENARIO_ADD_COMMENTS = 'add_comments_to_requests';
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'comments_to_request';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['comments_text', 'required', 'on' => self::SCENARIO_ADD_COMMENTS],
            [['comments_request_id', 'comments_user_id', 'created_at'], 'integer'],
            [['comments_text'], 'string', 'min' => 10, 'max' => 255, 'on' => self::SCENARIO_ADD_COMMENTS],
        ];
    }
    
    public function getComment() {
        return $this->hasOne(Requests::className(), ['requests_id' => 'comments_request_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'comments_user_id']);
    }    

    /*
     * Жадная выгрузка данных для формирования комментариев, соответствующих своей заявке
     */
    public static function findCommentsById($request_id) {
        
        $comments = self::find()
                ->joinWith(['user', 'user.client', 'user.employer'])
                ->andWhere(['comments_request_id' => $request_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        
        return $comments;
//        echo '<pre>';
//        var_dump($t); die();
    }
    
    public static function getCommentByRequest($request_id) {
        
        $query = (new \yii\db\Query)
                ->select('cr.created_at as date, cr.comments_text as text, '
                        . 'u.user_id as user, u.user_photo as photo')
                ->from('comments_to_request as cr')
                ->join('LEFT JOIN', 'user as u', 'u.user_id = cr.comments_user_id')
                ->where(['cr.comments_request_id' => $request_id])
                ->orderBy(['cr.created_at' => SORT_DESC])
                ->all();
        
        return $query;
    }
    
    /*
     * Сохранение комментария в бд
     */
    public function sendComment($request_id) {
        
        if ($this->validate()) {
            $this->comments_request_id = $request_id;
            $this->comments_user_id = Yii::$app->user->identity->id;
            return $this->save() ? true : false;
        }
        return false;
    }
    
    /*
     * Сохранение комментария в бд
     */
    public function sendComments($request_id) {
        
        if ($this->validate()) {
            
            $this->comments_request_id = $request_id;
            $this->comments_user_id = Yii::$app->user->identity->id;
            return $this->save() ? true : false;
            
        }
        return false;
    }    

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'comments_id' => 'Comments ID',
            'comments_request_id' => 'Comments Request ID',
            'comments_user_id' => 'Пользователь',
            'comments_text' => 'Ваш комментарий...',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }
}
