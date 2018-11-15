<?php

    namespace app\controllers;
    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Response;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\base\InvalidParamException;
    use yii\web\BadRequestHttpException;
    
    use app\models\RegistrationForm;
    use app\models\LoginForm;
    use app\models\User;
    use app\models\EmailConfirmForm;
    
    use app\models\PasswordResetRequestForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['index'],
//                'rules' => [
//                    [
//                        'actions' => ['index'],
//                        'allow' => true,
//                        'roles' => ['clients', 'clients_rent', 'administrator'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Главная страница. вход в систему
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
//    /*
//     * Форма регистрации
//     */
//    public function actionRegistration() {
//        
//        $model = new RegistrationForm();
//                
//        if ($model->load(Yii::$app->request->post())) {
//            if ($model->validate()) {
//                
//                Yii::$app->session->setFlash('registration-done', 'Для подтверждения регистрации пройдите по ссылке, указанной в письме');
//                
//                $data_model = new User();                
//                $data_model = $model->registration();
//                return $this->goHome();
//                
//            } else {
//                Yii::$app->session->setFlash('registration-error', 'При регистрации возникла ошибка');
//            }
//        }
//        
//        return $this->render('registration', ['model' => $model]);
//    }
//
    /**
     * Форма входа в систему
     */
    public function actionLogin() {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['clients/clients']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /*
     * Выход из системы
     */
    public function actionLogout() {
        
        Yii::$app->user->logout();
        return $this->goHome();
    }

//    /*
//     * Подтверждение регистрации
//     */
//    public function actionEmailConfirm($token) {
//        try {
//            $model = new EmailConfirmForm($token);
//        } catch (InvalidParamException $e) {
//            throw new BadRequestHttpException($e->getMessage());
//        }
//        
//        if ($model->confirmEmail()) {
//            Yii::$app->getSession()->setFlash('registration-done', 'Ваш Email успешно подтверждён');
//        } else {
//            Yii::$app->getSession()->setFlash('registration-error', 'Ошибка подтверждения Email');
//        }
// 
//        return $this->goHome();
//    }
    
    /*
     * Запрос на восстановление пароля
     */
    public function actionRequestPasswordReset() {
        
        $model = new PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('success', 'На указанный email были высланы инструкции для восстановления пароля');
            } else {
                Yii::$app->session->setFlash('error', 'При восстановлении пароля произошла ошибка');
            }
        }
        return $this->render('request-password-reset', ['model' => $model]);
        
    }
    
}
