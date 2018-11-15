<?php
    namespace app\models;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;
    use app\models\PersonalAccount;
    use app\models\Employers;
    

/**
 * Пользователи системы / роли
 */
    
class User extends ActiveRecord implements IdentityInterface
{
    
    /*
     * Статусы учетной записи пользователя
     * STATUS_DISABLED - пользователь не подтвердил регитрацию
     * STATUS_ENABLED - пользователь подтвердил регитрацию
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    const STATUS_BLOCK = 2;
    
    const SCENARIO_EDIT_PROFILE = 'edit user profile';
    const SCENARIO_EDIT_ADMINISTRATION_PROFILE = 'edit administration profile';
    const SCENARIO_EDIT_CLIENT_PROFILE = 'edit client profile';
    const SCENARIO_ADD_USER = 'add new user';
    
    /*
     * Дополнительные свойства для модели создания нового пользователя
     * 
     * @param integer $is_new Разрешение добавлять новости
     * @param integer $role Роль пользователя
     */
    public $is_new = false;
    public $role;

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /*
     *  Таблица из БД
     */
    public static function tableName() {
        return 'user';        
    }

    /*
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['user_login', 'required'],
            
            [['user_email', 'user_mobile'], 'required', 'on' => self::SCENARIO_EDIT_PROFILE],
            
            [['user_photo'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['user_photo'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            ['user_email', 'string', 'min' => 5, 'max' => 150, 'on' => self::SCENARIO_EDIT_PROFILE],
            ['user_email', 'email'],
            ['user_email', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Данный электронный адрес уже используется в системе',
                'on' => self::SCENARIO_EDIT_PROFILE,
            ],
            ['user_email', 'match',
                'pattern' => '/^[A-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
                'on' => self::SCENARIO_EDIT_PROFILE,
            ],
            
            ['user_mobile', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', 
                'on' => self::SCENARIO_EDIT_PROFILE,
            ],
            
            ['user_mobile', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_EDIT_PROFILE,
            ],
            
            [['user_mobile'], 'required', 'on' => self::SCENARIO_EDIT_ADMINISTRATION_PROFILE],
            
            ['user_mobile', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', 
                'on' => self::SCENARIO_EDIT_ADMINISTRATION_PROFILE,
            ],
            
            ['user_mobile', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_EDIT_ADMINISTRATION_PROFILE,
            ],
            
            [['user_email'], 'required', 'on' => self::SCENARIO_EDIT_CLIENT_PROFILE],
            
            [['user_check_email'], 'boolean'],
            
            [['is_new', 'role'], 'safe'],
            
        ];
    }

    /*
     * Свзязь с таблицей "Собственники"
     */    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'user_client_id']);
    }

    /*
     * Свзязь с таблицей "Арендаторы"
     */    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_id' => 'user_rent_id']);
    }  
    
    /*
     * Связь через промеуточную таблицу
     */
    public function getPersonalAccount() {
        return $this->hasMany(PersonalAccount::className(), ['account_id' => 'account_id'])
                ->viaTable('account_to_users', ['user_id' => 'user_id']);
    }
    
    /*
     * Свзяь с таблицей Сотрудники
     */
    public function getEmployer() {
        return $this->hasOne(Employers::className(), ['employers_id' => 'user_employer_id']);
    }
    
    /*
     * Поиск экземпляра identity, используя ID пользователя со статусом подтверденной регистрации
     * Для поддержки состояние аутентификации через сессии
     */    
    public static function findIdentity($id) {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ENABLED]);
    }

    /*
     * Аутентификация на основе токена, требуется объявить
     * В проекте не используется    
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new \yii\base\NotSupportedException('Аутентификация на основе токена не поддерживается');
    }

    /*
     *  Поиск имени пользователя    
     */
    public static function findByUsername($username) {
        return static::findOne(['user_login' => $username]);        
    }

    /*
     *  Поиск по ID пользователя
     */
    public static function findByID($user_id) {
        return static::findOne(['user_id' => $user_id]);        
    }    
    
    /*
     * Поиск пользователя по параметрам
     * @user (ID user)
     */    
    public static function findByUser($user) {
        return static::find()
                ->andWhere([
                    'user_id' => $user,
                    'status' => self::STATUS_ENABLED])
                ->one();
    }
    
    public static function findByEmail($email) {
        
        return self::find()
                ->where(['user_email' => $email])
                ->asArray()
                ->one();
    }

    /*
     *  Получить ID пользователя
     */
    public function getId() {
        return $this->user_id;
    }

    /*
     *  Получить ключ, используемый для cookie аутентификации
     */
    public function getAuthKey() {
        return $this->user_authkey;
    }

    /*
     *  Проверка ключа для аутентификации на основе cookie
     */
    public function validateAuthKey($autKey) {
        return $this->user_authkey === $authKey;
    }

    /*
     *  Проверка валидации пароля пользователя
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->user_password);
    }

    /*
     *  Генерация ключа в виде случайной строки    
     */
    public function generateAuthKey() {
        $this->user_authkey = Yii::$app->security->generateRandomString();
    }
    
    /*
     * Генерация ключа email_confirm_token для подтверждения регистрации по почте
     * Присваивается каждому пользователю при регистрации
     */
    public function generateEmailConfirmToken() {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }
    
    /*
     * Создаем хешь пароля четной записи
     */
    public function setUserPassword($password) {
        return $this->user_password = Yii::$app->security->generatePasswordHash($password);
    }
    /*
     * Поиск пользователя по сгенерированному ключу
     * Если запрашиваемы ключ найден, то меняем статус пользователя на STATUS_ENABLED
     */
    public static function findByEmailConfirmToken($email_confirm_token) {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => User::STATUS_DISABLED]);
    }
    
    /*
     * Поиск ID клиента
     */
    public static function findByClientId($user_id) {
        return static::findOne(['user_client_id' => $user_id]);
    }

    /*
     * Поиск ID сотрудника
     */
    public static function findByEmployerId($employer_id) {
        return static::findOne(['user_employer_id' => $employer_id]);
    }
    
    /*
     * Для связи таблицы пользователь и собственник (поиск по номеру телефона)
     */
    public function setClientByPhone($account_number) {
        $id = PersonalAccount::find()
                ->andWhere(['account_number' => $account_number])
                ->select('personal_clients_id')
                ->asArray()
                ->one();
        
        $this->user_client_id = $id['personal_clients_id'];
    }
    
    /*
     * Массив статусов учетной записи
     */
    public static function arrayUserStatus() {
        return [
            self::STATUS_ENABLED => 'Активен',
            self::STATUS_DISABLED => 'Не авторизирован',
            self::STATUS_BLOCK => 'Заблокирован собственником',
        ];
    }
    /*
     * Статус учетной записи пользователя
     */
    public function getUserStatus() {
        
        return ArrayHelper::getValue(self::arrayUserStatus(), $this->status);
    }
    
    /*
     * Загрузка фотографии в профиле пользователя
     */    
    public function uploadPhoto($file) {
        
        $current_image = $this->user_photo;
        
        if ($this->validate()) {
            if ($file) {
                $this->user_photo = $file;
                $dir = Yii::getAlias('images/users/');
                $file_name = $this->user_login . '_' . $this->user_photo->baseName . '.' . $this->user_photo->extension;
                $this->user_photo->saveAs($dir . $file_name);
                $this->user_photo = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->user_photo = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }
    
    /*
     * Получить список всех доступнвых ролей
     */
    public static function getRoles() {
        
        $list = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($list, 'name', 'description');
        
    }    
    
    /*
     * Назначение роли пользователю
     * 
     * @param string $role Роль
     * @param integer $user_id ID пользователя
     */
    public function setRole($role, $user_id) {
        $_role = Yii::$app->authManager->getRole($role);
        Yii::$app->authManager->assign($_role, $user_id);
    }
    
    /*
     * Получить имя роли пользователя
     */
    public static function getRole($name) {
        return ArrayHelper::getValue(self::getRoles(), $name);
    }
    
    /*
     * Метод сохранения электронной почты
     * 
     * Если пользователь меняет мобильный телефон, то 
     * номер меняется у сущности Пользователь/Собственник/Арендатор
     * 
     */
    public function updateEmailProfile() {
        
        if ($this->validate()) {
//            if (Yii::$app->user->can('AddNewRent')) {
//                $this->client->clients_mobile = $this->user_mobile;
//                $this->client->save();                
//            } else {
//                $this->rent->rents_mobile = $this->user_mobile;
//                $this->rent->save();
//            }
            $this->save();
            return true;
        }
        return false;
        
    }
    
    /*
     * Аватар пользователя
     */
    public function getPhoto() {
        
        if (empty($this->user_photo)) {
            return Yii::getAlias('@web') . '/images/no-avatar.jpg';
        }
        return Yii::getAlias('@web') . $this->user_photo;
        
    }
    
    /*
     * Настройка полей для форм
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User Id',
            'user_login' => 'Логин пользователя',
            'user_password' => 'Пароль пользователя',
            'user_email' => 'Электронная почта',
            'user_mobile' => 'Мобильный телефон',
            'user_photo' => 'Аватар',
            'user_check_email' => 'Разрешить e-mail оповещения',
            'user_authkey' => 'User Authkey',
            'date_create' => 'Дата регистрации',
            'updated_at' => 'Дата редактирования',
            'status' => 'Статус',
            'date_login' => 'Дата последнего входа',
            'is_new' => 'Добавлять новости',
            'role' => 'Роль',
        ];
    }
    
}
