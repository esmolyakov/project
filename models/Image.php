<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\Html;

/**
 * Модель, контролирующая сохранение изображений
 * Используется для расширения yii2-image
 */
class Image extends ActiveRecord
{
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['filePath', 'modelName', 'urlAlias'], 'required'],
            [['itemId', 'isMain'], 'integer'],
            [['filePath', 'urlAlias'], 'string', 'max' => 400],
            [['modelName'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 80],
        ];
    }
    
    /*
     * Найти файл по ID
     */
    public static function findById($file_id) {
        return self::find()
                ->where(['id' => $file_id])
                ->one();
    }
    
    /*
     * Получить полный путь к загруженному файлу
     */
    public function getImagePath($image) {
        return Yii::getAlias('@web') . '/upload/store/' . $image;
    }
    
    /*
     * Получить все документы, закрепленные за публикацией
     */
    public static function getAllDocByNews($id, $model_name) {
        return self::find()
                ->where(['itemId' => $id])
                ->andWhere(['modelName' => $model_name])
                ->asArray()
                ->all();
    }

    /*
     * Получить все документы, закрепленные за публикацией
     */
    public static function getAllFilesByHouse($house_id, $model_name) {
        return self::find()
                ->where(['itemId' => $house_id])
                ->andWhere(['modelName' => $model_name])
                ->asArray()
                ->all();
    }    
    
    /*
     * После удаления записи из БД, удаляем так же физический файл
     */
    public function afterDelete() {
        
        parent::afterDelete();
        $path = $this->filePath;
        @unlink(Yii::getAlias('@webroot') . '/upload/store/' . $path);
    }
    
    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filePath' => 'File Path',
            'itemId' => 'Item ID',
            'isMain' => 'Is Main',
            'modelName' => 'Model Name',
            'urlAlias' => 'Url Alias',
            'name' => 'Name',
        ];
    }
}
