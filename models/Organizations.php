<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;


/**
 * Организации, предоставляющие услуги ЖКХ
 */
class Organizations extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['organizations_name'], 'string', 'max' => 70],
        ];
    }
    
    public static function getOrganizations() {
        $all_organizations = self::find()->asArray()->all();
        return ArrayHelper::map($all_organizations, 'organizations_id', 'organizations_name');
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'organizations_id' => 'Organizations ID',
            'organizations_name' => 'Organizations Name',
        ];
    }
}
