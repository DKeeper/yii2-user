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
use dkeeper\yii2\user\helpers\ModuleTrait;

/**
 * This is the model class for table "{tablePrefix}user".
 *
 * @property string    $id
 * @property integer   $status
 * @property string    $email
 * @property string    $new_email
 * @property string    $username
 * @property string    $password
 * @property string    $auth_key
 * @property string    $login_ip
 * @property int    $last_login
 * @property string    $create_ip
 * @property int    $created_at
 * @property int    $updated_at
 * @property string    $ban_to
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
            return true;
        }
        return false;
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


    /**
     * Send email confirmation to user
     *
     * @return int
     */
    public function sendEmailConfirmation()
    {
        /** @var Mailer $mailer */
        /** @var Message $message */

        // modify view path to module views
        $mailer           = Yii::$app->mailer;
        $oldViewPath      = $mailer->viewPath;
        $mailer->viewPath = $this->getModule()->emailViewPath;

        // send email
        $user    = $this;
        $profile = $user->profile;
        $email   = $user->new_email !== null ? $user->new_email : $user->email;
        $subject = Yii::$app->id . " - " . Yii::t("user", "Email Confirmation");
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
} 