<?php

namespace common\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;
use common\models\Asset;

/**
 * This is the model class for table "tbl_user".
 *
 * @property string    $id
 * @property string    $role_id
 * @property integer   $status
 * @property string    $email
 * @property string    $new_email
 * @property string    $username
 * @property string    $password
 * @property string    $auth_key
 * @property string    $api_key
 * @property string    $login_ip
 * @property string    $login_time
 * @property string    $create_ip
 * @property string    $create_time
 * @property string    $update_time
 * @property string    $ban_time
 * @property string    $ban_reason
 *
 * @property string    $salt
 * @property string    $name
 * @property string    $description
 * @property string    $state
 *
 * @property Asset     $avatar
 * @property Profile   $profile
 * @property Role      $role
 * @property UserKey[] $userKeys
 * @property UserAuth[] $userAuths
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var int Inactive status
     */
    const STATUS_INACTIVE = 0;

    /**
     * @var int Active status
     */
    const STATUS_ACTIVE = 1;

    /**
     * @var int Unconfirmed email status
     */
    const STATUS_UNCONFIRMED_EMAIL = 2;

    /**
     * @var int Unconfirmed email status
     */
    const STATUS_BANNED_FOREVER = 3;

    /**
     * @var int Key for hashing password
     */
    const SECURITY_KEY = '6763750ac11ad4cbc97c4035268e7af5849fc006';

    /**
     * @var string Current password - for account page updates
     */
    public $currentPassword;

    /**
     * @var string New password - for registration and changing password
     */
    public $newPassword;

    /**
     * @var string New password confirmation - for reset
     */
    public $newPasswordConfirm;

    /**
     * @var array Permission cache array
     */
    protected $_access = [];

    /**
     * @var File User photo
     */
    public $avatar;

    /**
     * @var string Coordinates data for crop image
     */
    public $cropData;

    /**
     * @var string Captcha
     */
    public $captcha;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return static::getDb()->tablePrefix . "users";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // set initial rules
        $rules = [
            // general email and username rules
            [['email', 'username'], 'string', 'max' => 255],
            [['email', 'username'], 'unique', 'on' => ['register', 'create']],
            [['email', 'username'], 'filter', 'filter' => 'trim'],
            [['email'], 'email', 'message' => '{attribute} не является правильным адресом.'],
            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_-]+$/u', 'message' => Yii::t('user', '{attribute} can contain only letters, numbers, "_" and "-"')],

            // password rules
            [['newPassword'], 'string', 'min' => 3],
            [['newPassword'], 'filter', 'filter' => 'trim'],
            [['newPassword'], 'required', 'on' => ['register', 'reset'], 'message' => 'Пожалуйста, введите Пароль'],
            [['newPasswordConfirm'], 'required', 'on' => ['register', 'reset'], 'message' => 'Пожалуйста, подтвердите Пароль'],
            [['newPasswordConfirm'], 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли не совпадают'],

            // account page
            [['currentPassword'], 'required', 'on' => ['account']],
            [['currentPassword'], 'validateCurrentPassword', 'on' => ['account']],

            // admin crud rules
            [['role_id', 'status'], 'required', 'on' => ['admin']],
            [['role_id', 'status'], 'integer', 'on' => ['admin']],
            [['ban_time'], 'integer', 'on' => ['admin']],
            [['ban_reason'], 'string', 'max' => 255, 'on' => 'admin'],

            // required rules
            [['email', 'username'], 'required', 'message' => 'Пожалуйста, введите {attribute}'],

            // image
            [['avatar'], 'file', 'extensions' => 'jpeg, jpg , gif, png'],
            [['cropData'], 'safe'],

            // captcha
            ['captcha', 'required', 'on' => ['register']],
            ['captcha', 'captcha', 'on' => ['register']],
        ];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        // Auto deactivating ban
        if(isset($this->ban_time) && strtotime($this->ban_time) < time()) {
            $this->ban_time = null;
            $this->save(false, ['ban_time']);
        }
    }

    /**
     * Validate current password (account page)
     */
    public function validateCurrentPassword()
    {
        if (!$this->verifyPassword($this->currentPassword)) {
            $this->addError("currentPassword", "Не верный пароль");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'role_id'     => 'Роль',
            'status'      => 'Статус',
            'email'       => 'E-mail',
            'new_email'   => 'Новый Email',
            'username'    => 'Логин',
            'password'    => 'Пароль',
            'auth_key'    => Yii::t('user', 'Auth Key'),
            'api_key'     => Yii::t('user', 'Api Key'),
            'login_ip'    => Yii::t('user', 'Login Ip'),
            'login_time'  => Yii::t('user', 'Login Time'),
            'create_ip'   => Yii::t('user', 'Create Ip'),
            'create_time' => Yii::t('user', 'Create Time'),
            'update_time' => Yii::t('user', 'Update Time'),
            'ban_time'    => 'Время бана',
            'ban_reason'  => 'Причина бана',

            'currentPassword' => 'Текущий пароль',
            'newPassword'     => 'Новый пароль',
            'newPasswordConfirm' => 'Подтверждение пароля',
            'avatar' => 'Аватар',
            'captha' => 'Каптча',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'value'      => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_time',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        $profile = Yii::$app->getModule("user")->model("Profile");
        return $this->hasOne($profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        $role = Yii::$app->getModule("user")->model("Role");
        return $this->hasOne($role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserKeys()
    {
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        return $this->hasMany($userKey::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuths()
    {
        return $this->hasMany(UserAuth::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(["api_key" => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Verify password
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        // old validation
        // if(strtotime($this->create_time) < strtotime('12-02-2009')) {
        
        $passwordHash = sha1("--$this->salt--$password--");
        if($passwordHash != $this->password) 
        {
            $digest = self::SECURITY_KEY;
            for ($i=0; $i < 10; $i++) { 
                $digest = sha1(implode('--', [$digest, $this->salt, $password, self::SECURITY_KEY]));
            }
            $passwordHash = $digest;
        }
        return $this->password == $passwordHash;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        // hash new password if set
        if ($this->newPassword) {
            $this->salt = Yii::$app->security->generateRandomString();
            if(!empty($this->create_time) && strtotime($this->create_time) < strtotime('12-02-2009')) {
                $this->password = sha1("--$this->salt--$this->newPassword--");
            } else {
                $digest = self::SECURITY_KEY;
                for ($i=0; $i < 10; $i++) { 
                    $digest = sha1(implode('--', [$digest, $this->salt, $this->newPassword, self::SECURITY_KEY]));
                }
                $this->password = $digest;
            }
        }

        if($this->status == self::STATUS_BANNED_FOREVER) {
            $this->ban_time = true;
        }

        // convert ban_time checkbox to date
        if ($this->ban_time === true || $this->ban_time === '1') {
            $this->ban_time = date("Y-m-d H:i:s", time() + 60*60*24*7);
        } else $this->ban_time = null;

        // ensure fields are null so they won't get set as empty string
        $nullAttributes = ["email", "username", "ban_time", "ban_reason"];
        foreach ($nullAttributes as $nullAttribute) {
            $this->$nullAttribute = $this->$nullAttribute ? $this->$nullAttribute : null;
        }

        return parent::beforeSave($insert);
    }

    /**
     * Set attributes for registration
     *
     * @param int    $roleId
     * @param string $userIp
     * @param string $status
     * @return static
     */
    public function setRegisterAttributes($roleId, $userIp, $status = null)
    {
        // set default attributes
        $attributes = [
            "role_id"   => $roleId,
            "create_ip" => $userIp,
            "auth_key"  => Yii::$app->security->generateRandomString(),
            "api_key"   => Yii::$app->security->generateRandomString(),
            "status"    => static::STATUS_ACTIVE,
        ];

        // determine if we need to change status based on module properties
        $emailConfirmation = Yii::$app->getModule("user")->emailConfirmation;
        $requireEmail      = Yii::$app->getModule("user")->requireEmail;
        $useEmail          = Yii::$app->getModule("user")->useEmail;
        if ($status) {
            $attributes["status"] = $status;
        }
        elseif ($emailConfirmation && $requireEmail) {
            $attributes["status"] = static::STATUS_INACTIVE;
        }
        elseif ($emailConfirmation && $useEmail && $this->email) {
            $attributes["status"] = static::STATUS_UNCONFIRMED_EMAIL;
        }

        // set attributes and return
        $this->setAttributes($attributes, false);
        return $this;
    }

    /**
     * Check and prepare for email change
     *
     * @return bool True if user set a `new_email`
     */
    public function checkAndPrepEmailChange()
    {
        // check if user is removing email address (only if Module::$requireEmail = false)
        if (trim($this->email) === "") {
            return false;
        }

        // check for change in email
        if ($this->email != $this->getOldAttribute("email")) {

            // change status
            $this->status = static::STATUS_UNCONFIRMED_EMAIL;

            // set `new_email` attribute and restore old one
            $this->new_email = $this->email;
            $this->email     = $this->getOldAttribute("email");

            return true;
        }

        return false;
    }

    /**
     * Update login info (ip and time)
     *
     * @return bool
     */
    public function updateLoginMeta()
    {
        // Save to session a previous login time for calculate new comments
        Yii::$app->session['prev_login_time'] = $this->login_time;
        // set data
        $this->login_ip   = Yii::$app->getRequest()->getUserIP();
        $this->login_time = date("Y-m-d H:i:s");

        // save and return
        return $this->save(false, ["login_ip", "login_time"]);
    }

    /**
     * Confirm user email
     *
     * @return bool
     */
    public function confirm()
    {
        // update status
        $this->status = static::STATUS_ACTIVE;

        // update new_email if set
        if ($this->new_email) {
            $this->email     = $this->new_email;
            $this->new_email = null;
        }

        // save and return
        return $this->save(false, ["email", "new_email", "status"]);
    }

    /**
     * Check if user can do specified $permission
     *
     * @param string    $permissionName
     * @param array     $params
     * @param bool      $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
         // check for auth manager rbac
        $auth = Yii::$app->getAuthManager();
        if ($auth) {
            if ($allowCaching && empty($params) && isset($this->_access[$permissionName])) {
                return $this->_access[$permissionName];
            }
            $access = $auth->checkAccess($this->getId(), $permissionName, $params);
            if ($allowCaching && empty($params)) {
                $this->_access[$permissionName] = $access;
            }

            return $access;
        }

        switch ($permissionName) {
            case 'changePassword':
            case 'changeUser':
            case 'changeBan':
                return $this->role_id == Role::ROLE_ADMIN;
            case 'comment':
                return isset($this->ban_time) || $this->status == self::STATUS_BANNED_FOREVER ||
                    $this->status == self::STATUS_INACTIVE ? false : true;
            default:
                break;
        }

        // otherwise use our own custom permission (via the role table)
        return $this->role->checkPermission($permissionName);
    }

    /**
     * Get display name for the user
     *
     * @var string $default
     * @return string|int
     */
    public function getDisplayName($default = "")
    {
        $profile = $this->getProfile();

        // define possible fields
        $possibleNames = [
            $this->profile->full_name,
            $this->username,
            $this->email,
            $this->id,
        ];

        // go through each and return if valid
        foreach ($possibleNames as $possibleName) {
            if (!empty($possibleName)) {
                return $possibleName;
            }
        }

        return $default;
    }

    /**
     * Send email confirmation to user
     *
     * @param UserKey $userKey
     * @return int
     */
    public function sendEmailConfirmation($userKey)
    {
        /** @var Mailer $mailer */
        /** @var Message $message */

        // modify view path to module views
        $mailer           = Yii::$app->mailer;
        $oldViewPath      = $mailer->viewPath;
        $mailer->viewPath = Yii::$app->getModule("user")->emailViewPath;

        // send email
        $user    = $this;
        $profile = $user->profile;
        $email   = $user->new_email !== null ? $user->new_email : $user->email;
        $subject = "Подтверждение регистрации на dynamomania.com";
        $message  = $mailer->compose('confirmEmail', compact("subject", "user", "profile", "userKey"))
            ->setTo($email)
            ->setSubject($subject);

        // check for messageConfig before sending (for backwards-compatible purposes)
        if (empty($mailer->messageConfig["from"])) {
            $message->setFrom(Yii::$app->params["adminEmail"]);
        }
        $result = $message->send();

        // restore view path and return result
        $mailer->viewPath = $oldViewPath;
        return $result;
    }

    /**
     * Get list of statuses for creating dropdowns
     *
     * @return array
     */
    public static function statusDropdown()
    {
        // get data if needed
        static $dropdown;
        if ($dropdown === null) {

            $dropdown[self::STATUS_ACTIVE] = self::statusHumanName(self::STATUS_ACTIVE);
            $dropdown[self::STATUS_INACTIVE] = self::statusHumanName(self::STATUS_INACTIVE);
            $dropdown[self::STATUS_UNCONFIRMED_EMAIL] = self::statusHumanName(self::STATUS_UNCONFIRMED_EMAIL);
            $dropdown[self::STATUS_BANNED_FOREVER] = self::statusHumanName(self::STATUS_BANNED_FOREVER);

        }
        return $dropdown;
    }

    /**
     * Get status human name
     *
     * @param int $status
     * @return string
     */
    public static function statusHumanName($status)
    {
        if ($status == self::STATUS_ACTIVE) return 'Активный';
        elseif ($status == self::STATUS_INACTIVE) return 'Неактивный';
        elseif ($status == self::STATUS_UNCONFIRMED_EMAIL) return 'Не подтвержденный';
        elseif ($status == self::STATUS_BANNED_FOREVER) return 'Забанен навсегда';

        return 'Не определено';
    }

    /**
     * @return Asset
     */
    public function getAsset($thumbnail = Asset::THUMBNAIL_CONTENT)
    {
        $asset = Asset::getAssets($this->id, Asset::ASSETABLE_USER, $thumbnail, true);
        return $asset;
    }

    /**
     * @return string Url to profile
     */
    public function getUrl()
    {
        return Url::to('/blogs/'.$this->id);
    }

    /**
     * Get current status
     *
     * @return string
     */
    public function getStatus()
    {
        return self::statusHumanName($this->status);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Album::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssets()
    {
        return $this->hasMany(Asset::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionUsers()
    {
        return $this->hasMany(QuestionUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['user_id' => 'id']);
    }

    /**
     * Return true if current user has banned ip
     * @return boolean 
     */
    public static function hasBannedIP()
    {
        $userIP = Yii::$app->getRequest()->getUserIP();
        $userIPlong = ip2long($userIP);
        if($userIPlong == -1 || $userIPlong === false) {
            return false;
        }
        $bannedIPs = \common\models\BannedIP::find()
            ->where([
                'is_active' => 1,
            ])->all();
        foreach ($bannedIPs as $bannedIP) {
            if(!isset($bannedIP->end_ip_num) || empty($bannedIP->end_ip_num)) {
                if($userIPlong == $bannedIP->start_ip_num) return true;
            } else {
                if($userIPlong >= $bannedIP->start_ip_num && $userIPlong <= $bannedIP->end_ip_num) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Return true if user is subscribed
     * @return boolean 
     */
    public function isSubscribed()
    {
        $subscribing = \common\models\Subscribing::find()
            ->where(['email' => $this->email])
            ->one();
        return isset($subscribing->id);
    }

    /**
     * Return true if user is subscribed
     * @return boolean 
     */
    public function getUnsubscribeKey()
    {
        $subscribing = \common\models\Subscribing::find()
            ->where(['email' => $this->email])
            ->one();
        if(isset($subscribing->id)) {
            return md5($subscribing->id.$subscribing->email);
        }
        return '';
    }
}