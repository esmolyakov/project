<?php

    namespace app\models;
    use yii\base\Model;
    use app\models\User;
    use Yii;

class PasswordResetRequestForm extends Model {
    
    public $email;
    
    /*
     * Правила валидации поля email для формы восстановления пароля
     */
    public function rules() {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => 'app\models\User',
                'filter' => [
                    'status' => User::STATUS_ENABLED,
                ],
                'targetAttribute' => 'user_email',
                'message' => 'Указанный электронный адрес не найден',
            ]
        ];
    }
    
    /*
     * Поиск пользователя по указанному email
     * Отправка письма на указанный адрес со случайно сгенерированным паролем
     */
    public function resetPassword() {
        $user = User::findByEmail($this->email);
        
        if (!$user) {
            return false;
        }
        
        // Генерируем новый пароль (случайные 6 символов)
        $new_password = Yii::$app->security->generateRandomString(6);
        // Хешируем новый пароль
        $user->user_password = Yii::$app->security->generatePasswordHash($new_password);
            
        if ($user->save()) {
            $this->sendEmail('ResetPassword', 'Восстановление пароля', ['new_password' => $new_password]);
        }
        return true;
    }
    
    /*
     * Отправка письма с новым паролем
     */
    public function sendEmail($view, $subject, $params = []) {
        $message = Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                ->setFrom('email-confirm@site.com')
                ->setTo($this->email)
                ->setSubject($subject)
                ->send();
        return $message;
    }
    
}
