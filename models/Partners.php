<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use app\models\News;

/**
 * Партнеры (Контрагенты)
 */
class Partners extends ActiveRecord
{
    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['partners_name', 'partners_adress'], 'required'],
            [['partners_name'], 'string', 'max' => 170],
            [['partners_adress'], 'string', 'max' => 255],
        ];
    }
    
    /*
     * Связь с таблицей Новости
     */
    public function getNews() {
        return $this->hasMany(News::className(), ['news_partner_id' => 'partners_id']);
    }
    
    /*
     * Получить список всех партнеров
     */
    public static function getAllParnters() {
        
        $array = self::find()
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'partners_id', 'partners_name');
    }

    /**
     * Атрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'partners_id' => 'Partners ID',
            'partners_name' => 'Partners Name',
            'partners_adress' => 'Partners Adress',
        ];
    }
}
