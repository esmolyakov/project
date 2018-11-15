<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;

/**
 * Форма добавления комментарией в заявке
 */
class CommentForm extends Model {
    
    public $comments_text;
    
    public function rules() {
        return [
            ['comments_text', 'required'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'comments_text' => 'Комментарий',
        ];
    }
    
}
