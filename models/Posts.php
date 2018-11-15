<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\Departments;

/**
 * Должности/Специализации
 */
class Posts extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['posts_department_id'], 'integer'],
            [['posts_name'], 'string', 'max' => 150],
        ];
    }
    
    /*
     * Связь с таблицей Подразделения
     */
    public function getDepartment() {
        return $this->hasOne(Departments::className(), ['departments_id' => 'posts_department_id']);
    }
    
    public static function getArrayPosts() {
        $array = self::find()->asArray()->all();
        return ArrayHelper::map($array, 'posts_id', 'posts_name');
    }

    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'posts_id' => 'Posts ID',
            'posts_department_id' => 'Posts Department ID',
            'posts_name' => 'Posts Name',
        ];
    }
}
