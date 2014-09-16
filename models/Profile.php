<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 15.09.14
 * @time 21:38
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\models;

use Yii;
use yii\db\ActiveRecord;
use dkeeper\yii2\user\helpers\ModuleTrait;

/**
 * @property integer    $id
 * @property integer    $user_id
 * @property string     $first_name
 * @property string     $last_name
 *
 * @property \dkeeper\yii2\user\models\User $user
 */
class Profile extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return static::getDb()->tablePrefix . "profile";
    }

    public function rules(){
        return [
            [['user_id'], 'integer'],
            [['user_id'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'user_id' => Yii::t('user', 'User'),
            'last_name' => Yii::t('user', 'Last name'),
            'first_name' => Yii::t('user', 'First name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        /** @var $profile \dkeeper\yii2\user\models\User */
        $profile = $this->getModule()->model('user');
        return $this->hasOne($profile::className(), ['id' => 'user_id']);
    }
}
