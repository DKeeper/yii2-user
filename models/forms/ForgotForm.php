<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.09.14
 * @time 19:53
 * Created by JetBrains PhpStorm.
 */

namespace dkeeper\yii2\user\models\forms;

use Yii;
use yii\base\Model;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;
use dkeeper\yii2\user\helpers\ModuleTrait;

class ForgotForm extends Model
{
    use ModuleTrait;

    /**
     * @var string username|email|phone
     */
    public $search;

    /**
     * @var string type field
     */
    public $type;

    /**
     * @var \dkeeper\yii2\user\models\User
     */
    protected $_user;

    public function rules() {
        $rules = [
            [["search","type"], "required"],
            [["search","type"], "string", "max" => 255],
            [["search"], "email", "on" => "email"],
            [["search"], "filter", "filter" => "trim"],
        ];

        // Include Module rules
        foreach($this->getModule()->fieldRules as $name => $data){
            $rules[] = [
                ['search'],
                $data['type'],
                'pattern' => $data['pattern'],
                'message' => Yii::t('user',$data['message']),
                'on' => $name,
            ];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $msg = "Username";

        if($this->getModule()->loginEmail) $msg .= "/Email";

        if($this->getModule()->loginPhone) $msg .= "/Phone";

        return [
            "search" => Yii::t("user", $msg),
            "type" => Yii::t("user", "Type of field"),
        ];
    }

    /**
     * Send forgot email
     *
     * @return bool
     */
    public function sendForgotEmail()
    {
        /** @var Mailer $mailer */
        /** @var Message $message */
        /** @var \dkeeper\yii2\user\models\UserKey $userKey */

        // validate
        if ($this->validate() && $this->getUser()) {

            /** @var $userKey \dkeeper\yii2\user\models\UserKey */
            $userKey = $this->getModule()->model("userKey");
            $userKey = $userKey::generate($this->_user->id, $userKey::PASSWORD_RESET, $this->getModule()->resetKeyDuration);

            $mailer           = Yii::$app->mailer;
            $oldViewPath      = $mailer->viewPath;
            $mailer->viewPath = "@vendor/dkeeper/yii2-user/views/mail";

            // send email
            $subject = Yii::$app->id . " - " . Yii::t("user", "Forgot password");
            $key = $userKey->key_value;
            $type = $userKey::PASSWORD_RESET;
            $message  = $mailer->compose('forgotPassword', compact("subject", "user", "key", "type"))
                ->setTo($this->_user->email)
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

        return false;
    }

    /**
     * @return boolean
     */
    protected function getUser(){
        /** @var $user \dkeeper\yii2\user\models\User */
        $user = $this->getModule()->model('user');
        $query = $user::find();
        switch($this->type){
            case 'email':
                $query->andWhere(["email"=>$this->search]);
                break;
            case 'phone':
                $query->andWhere(["phone"=>$this->search]);
                break;
            default:
                $query->andWhere(["username"=>$this->search]);
                break;
        }
        $this->_user = $query->one();

        if(!isset($this->_user)) $this->addError("search",Yii::t("user","Username not found"));

        return isset($this->_user);
    }
}
