<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 11.09.14
 * @time 20:25
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\models\search;

use dkeeper\yii2\user\models\User;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class UserSearch extends User
{
    public function rules()
    {
        return [
            [['id', 'status', 'phone', 'last_login', 'created_at', 'updated_at', 'ban_to'], 'integer'],
            [['username', 'login_ip', 'create_ip', 'email', 'ban_reason'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'phone' => $this->phone,
            'last_login' => $this->last_login,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ban_to' => $this->ban_to
        ]);

        $query->andFilterWhere(['like' , 'username' , $this->username])
            ->andFilterWhere(['like' , 'login_ip' , $this->login_ip])
            ->andFilterWhere(['like' , 'create_ip' , $this->create_ip])
            ->andFilterWhere(['like' , 'email' , $this->email])
            ->andFilterWhere(['like' , 'ban_reason' , $this->ban_reason]);

        return $dataProvider;
    }
}
