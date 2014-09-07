<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 02.09.14
 * @time 22:05
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\models;

use Yii;
use yii\base\Model;
use dkeeper\yii2\user\helpers\ModuleTrait;

class LoginForm extends Model
{
    use ModuleTrait;

    /**
     * @var string Username and/or email
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var bool If true, users will be logged in for $loginDuration
     */
    public $rememberMe = true;

    /**
     * @var \dkeeper\yii2\user\models\User
     */
    protected $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [["username", "password"], "required"],
            ["username", "validateUser"],
            ["username", "validateUserStatus"],
            ["password", "validatePassword"],
            ["rememberMe", "boolean"],
        ];
    }

    /**
     * Validate user
     */
    public function validateUser()
    {
        // check for valid user
        $user = $this->getUser();
        if (!$user) {

            // calculate error message
            if ($this->getModule()->loginEmail && $this->getModule()->loginUsername) {
                $errorAttribute = "Email/username";
            } elseif ($this->getModule()->loginEmail) {
                $errorAttribute = "Email";
            } else {
                $errorAttribute = "Username";
            }
            $this->addError("username", Yii::t("user", "$errorAttribute not found"));
        }
    }

    /**
     * Validate user status
     */
    public function validateUserStatus()
    {
        // check for ban status
        $user = $this->getUser();
        if ($user->ban_to && $user->ban_to > time()) {
            $this->addError("username", Yii::t("user", "User is banned - {banReason}", [
                "banReason" => $user->ban_reason,
            ]));
        }

        // check status and resend email if inactive
        if ($user->status == $user::INACTIVE) {
            $user->sendEmailConfirmation();
            $this->addError("username", Yii::t("user", "Confirmation email resent"));
        }
    }

    /**
     * Validate password
     */
    public function validatePassword()
    {
        // skip if there are already errors
        if ($this->hasErrors()) {
            return;
        }

        // check password
        /** @var \amnah\yii2\user\models\User $user */
        $user = $this->getUser();
        if (!$user->verifyPassword($this->password)) {
            $this->addError("password", Yii::t("user", "Incorrect password"));
        }
    }

    /**
     * Get user based on email and/or username
     *
     * @return \dkeeper\yii2\user\models\User|null
     */
    public function getUser()
    {
        // check if we need to get user
        if ($this->_user === false) {

            /**
             * @var $user \dkeeper\yii2\user\models\User
             */
            // build query based on email and/or username login properties
            $user = $this->getModule()->model("user");
            $user = $user::find();
            if ($this->getModule()->loginEmail) {
                $user->orWhere(["email" => $this->username]);
            }
            if ($this->getModule()->loginUsername) {
                $user->orWhere(["username" => $this->username]);
            }

            // get and store user
            $this->_user = $user->one();
        }

        // return stored user
        return $this->_user;
    }

    /**
     * Validate and log user in
     *
     * @param int $loginDuration
     * @return bool
     */
    public function login($loginDuration)
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $loginDuration : 0);
        }

        return false;
    }
}
