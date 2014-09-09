<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 09.09.14
 * @time 17:45
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\models;

use Yii;
use yii\db\ActiveRecord;
use dkeeper\yii2\user\helpers\ModuleTrait;
use yii\behaviors\TimestampBehavior;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $key_value
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $consume_to
 *
 * @property User $user;
 */
class UserKey extends ActiveRecord
{
    use ModuleTrait;

    const EMAIL_ACTIVATE = 1;

    const EMAIL_CHANGE = 2;

    const PHONE_ACTIVATE = 3;

    const PHONE_CHANGE = 4;

    const PASSWORD_RESET = 5;

    public static function tableName(){
        return static::getDb()->tablePrefix . "user_key";
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

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getUser()
    {
        $user = $this->getModule()->model("user");
        return $this->hasOne($user::className(), ['id' => 'user_id']);
    }

    public static function generate($userId, $type, $consumeDuration = null)
    {
        // attempt to find existing record
        // otherwise create new
        $model = static::findActiveByUser($userId, $type);
        if (!$model) {
            $model = new static();
        }

        if(isset($consumeDuration)) $consumeDuration += time();
        // set/update data
        $model->user_id     = $userId;
        $model->type        = $type;
        $model->consume_to = $consumeDuration;
        $model->key_value         = Yii::$app->security->generateRandomString();
        $model->save(false);
        return $model;
    }

    public static function findActiveByUser($userId, $type)
    {
        $now = time();
        return static::find()
            ->where([
            "user_id"      => $userId,
            "type"         => $type,
        ])
            ->andWhere("([[consume_to]] >= '$now' or [[consume_to]] is NULL)")
            ->one();
    }

    public static function findActiveByKey($key, $type)
    {
        $now = time();
        return static::find()
            ->where([
            "key_value"          => $key,
            "type"         => $type,
        ])
            ->andWhere("([[consume_to]] >= '$now' or [[consume_to]] is NULL)")
            ->one();
    }
}
