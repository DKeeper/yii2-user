<?php
/**
 * @author: Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date: 02.09.14
 * @time: 11:01
 * Created by PhpStorm.
 */

namespace dkeeper\yii2\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;
use dkeeper\yii2\user\helpers\ModuleTrait;

/**
 * This is the model class for table "{tablePrefix}user".
 *
 * @property integer    $id
 * @property integer   $status
 * @property string    $email
 * @property integer    $email_confirm
 * @property string    $new_email
 * @property integer    $phone
 * @property integer    $phone_confirm
 * @property integer    $new_phone
 * @property string    $username
 * @property string    $password
 * @property string    $auth_key
 * @property string    $login_ip
 * @property integer    $last_login
 * @property string    $create_ip
 * @property integer    $created_at
 * @property integer    $updated_at
 * @property integer    $ban_to
 * @property string    $ban_reason
 *
 */
class User extends ActiveRecord implements IdentityInterface {
    use ModuleTrait;

    /**
     * @var int Inactive status
     */
    const INACTIVE = 0;

    /**
     * @var int Active status
     */
    const ACTIVE = 1;

    /**
     * @var int Unconfirmed email status
     */
    const UNCONFIRMED_EMAIL = 2;

    /**
     * @var int Unconfirmed phone status
     */
    const UNCONFIRMED_PHONE = 3;

    /**
     * @var string New password - for registration and changing password
     */
    public $newPassword;

    /**
     * @var string New password confirmation - for reset
     */
    public $newPasswordConfirm;

    /**
     * @var string Current password - for account page updates
     */
    public $currentPassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // set initial rules
        $rules = [
            // general email and username rules
            [['phone'], 'integer'],
            [['email', 'username'], 'string', 'max' => 255],
            [['email', 'username', 'phone'], 'unique'],
            [['email', 'username', 'phone'], 'filter', 'filter' => 'trim'],
            [['email'], 'email'],

            //confirm fields
            [['email_confirm','phone_confirm'], 'default', 'value' => true],

            // password rules
            [['newPassword'], 'string', 'min' => 3],
            [['newPassword'], 'filter', 'filter' => 'trim'],
            [['newPassword'], 'required', 'on' => ['register', 'reset']],
            [['newPasswordConfirm'], 'required', 'on' => ['reset']],
            [['newPasswordConfirm'], 'compare', 'compareAttribute' => 'newPassword', 'message' => Yii::t('user','Passwords do not match')],

            // account page
            [['currentPassword'], 'required', 'on' => ['account']],
            [['currentPassword'], 'validateCurrentPassword', 'on' => ['account']],

            // admin crud rules
            [['ban_to'], 'integer', 'on' => [$this->getModule()->adminRole]],
            [['ban_reason'], 'string', 'max' => 255, 'on' => $this->getModule()->adminRole],
        ];

        // add custom rules from Module
        foreach($this->getModule()->fieldRules as $name => $data){
            $rules[] = [
                [$name],
                $data['type'],
                'pattern' => $data['pattern'],
                'message' => Yii::t('user',$data['message'])
            ];
        }

        // add required rules for email/username depending on module properties
        $requireFields = ["requireEmail", "requireUsername", "requirePhone"];
        foreach ($requireFields as $requireField) {
            if (Yii::$app->getModule("user")->$requireField) {
                $attribute = strtolower(substr($requireField, 7)); // "email" or "username"
                $rules[]   = [$attribute, "required"];
            }
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('user', 'ID'),
            'status'      => Yii::t('user', 'Status'),
            'email'       => Yii::t('user', 'Email'),
            'email_confirm' => Yii::t('user', 'Email confirmation'),
            'new_email'   => Yii::t('user', 'New email'),
            'phone'       => Yii::t('user', 'Phone'),
            'phone_confirm' => Yii::t('user', 'Phone confirmation'),
            'new_phone'   => Yii::t('user', 'New phone'),
            'username'    => Yii::t('user', 'Username'),
            'password'    => Yii::t('user', 'Password'),
            'auth_key'    => Yii::t('user', 'Auth key'),
            'login_ip'    => Yii::t('user', 'Login IP'),
            'last_login'  => Yii::t('user', 'Last login time'),
            'create_ip'   => Yii::t('user', 'Create IP'),
            'create_at' => Yii::t('user', 'Created at'),
            'update_at' => Yii::t('user', 'Updated at'),
            'ban_to'    => Yii::t('user', 'Ban to'),
            'ban_reason'  => Yii::t('user', 'Ban reason'),

            'currentPassword' => Yii::t('user', 'Current password'),
            'newPassword'     => Yii::t('user', 'Password'),
            'newPasswordConfirm'     => Yii::t('user', 'Password confirm'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return static::getDb()->tablePrefix . "user";
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            }
            if ($this->newPassword) {
                $this->password = Yii::$app->security->generatePasswordHash($this->newPassword);
            }
            return true;
        }
        return false;
    }

    public function afterLogin(){
        $this->login_ip   = Yii::$app->getRequest()->getUserIP();
        $this->last_login = time();
        return $this->save(false, ["login_ip", "last_login"]);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param null $type
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function sendPhoneConfirmation($userKey)
    {

    }

    /**
     * Send email confirmation to user
     *
     * @param $userKey
     * @return int
     */
    public function sendEmailConfirmation($userKey)
    {
        /** @var Mailer $mailer */
        /** @var Message $message */

        // modify view path to module views
        $mailer           = Yii::$app->mailer;
        $oldViewPath      = $mailer->viewPath;
        $mailer->viewPath = "@vendor/dkeeper/yii2-user/views/mail";

        // send email
        $user    = $this;
        $email   = $user->new_email !== null ? $user->new_email : $user->email;
        $subject = Yii::$app->id . " - " . Yii::t("user", "Email confirmation");
        $key = $userKey->key_value;
        $type = $userKey::EMAIL_ACTIVATE;
        $message  = $mailer->compose('confirmEmail', compact("subject", "user", "key", "type"))
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
     * Verify password
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Get display name for the user
     *
     * @var string $default
     * @return string|int
     */
    public function getDisplayName($default = "")
    {
        // define possible fields
        $possibleNames = [
            "username",
            "email",
            "id",
        ];

        // go through each and return if valid
        foreach ($possibleNames as $possibleName) {
            if (!empty($this->$possibleName)) {
                return $this->$possibleName;
            }
        }

        return $default;
    }

    public function setRegisterAttributes($status = null)
    {
        // set default attributes
        $attributes = [
            "status"    => static::ACTIVE,
        ];

        if($this->isNewRecord) $this->auth_key = Yii::$app->security->generateRandomString();

        $emailConfirmation = $this->getModule()->emailConfirmation;
        if($emailConfirmation && $this->isNewRecord) $this->email_confirm = false;
        $phoneConfirmation = $this->getModule()->phoneConfirmation;
        if($phoneConfirmation && $this->isNewRecord) $this->phone_confirm = false;

        if ($status) {
            $attributes["status"] = $status;
        } elseif ( ($emailConfirmation && !$this->email_confirm) || ($phoneConfirmation && !$this->phone_confirm) ) {
            $attributes["status"] = static::INACTIVE;
        }

        // set attributes and return
        $this->setAttributes($attributes, false);
        return $this;
    }

    /**
     * Confirm user
     *
     * @param $attr string
     * @return bool
     */
    public function confirm($attr)
    {
        // update new_email if set
        switch($attr){
            case "email":
                $this->email_confirm = true;
                if ($this->new_email) {
                    $this->email     = $this->new_email;
                    $this->new_email = null;
                }
                break;
            case "phone":
                $this->phone_confirm = true;
                if ($this->new_phone) {
                    $this->phone     = $this->new_phone;
                    $this->new_phone = null;
                }
                break;
        }

        $this->setRegisterAttributes();

        // save and return
        return $this->save(false);
    }

    /**
     * Validate current password (account page)
     */
    public function validateCurrentPassword()
    {
        if (!$this->verifyPassword($this->currentPassword)) {
            $this->addError("currentPassword", "Current password incorrect");
        }
    }
}