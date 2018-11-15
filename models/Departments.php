<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\Posts;

/**
 * Подразделения
 */
class Departments extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['departments_name'], 'string', 'max' => 150],
        ];
    }

    /*
     * Связь с таблицей Должности
     */
    public function getPost() {
        return $this->hasMany(Posts::className(), ['posts_department_id' => 'departments_id']);
    }    
    
    public static function getArrayDepartments() {
        $array = self::find()->asArray()->all();
        return ArrayHelper::map($array, 'departments_id', 'departments_name');
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'departments_id' => 'Departments ID',
            'departments_name' => 'Departments Name',
        ];
    }
}
