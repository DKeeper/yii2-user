<?php

use yii\db\Schema;
use yii\db\Migration;
use dkeeper\yii2\user\models\User;
use dkeeper\yii2\user\models\UserKey;

class m140908_144113_Init_User extends Migration
{
    public function safeUp()
    {
        $this->createTable(User::tableName(), [
            "id" => Schema::TYPE_INTEGER . " not null auto_increment primary key",
            "status" => "tinyint not null",
            "email" => "varchar(255) null default null",
            "email_confirm" => "boolean default true",
            "new_email" => "varchar(255) null default null",
            "phone" => "varchar(255) null default null",
            "phone_confirm" => "boolean default true",
            "new_phone" => "varchar(255) null default null",
            "username" => "varchar(255) null default null",
            "password" => "varchar(255) null default null",
            "auth_key" => "varchar(255) null default null",
            "login_ip" => "varchar(45) null default null",
            "last_login" => Schema::TYPE_INTEGER . " null default null",
            "create_ip" => "varchar(45) null default null",
            "created_at" => Schema::TYPE_INTEGER . " null default null",
            "updated_at" => Schema::TYPE_INTEGER . " null default null",
            "ban_to" => Schema::TYPE_INTEGER . " null default null",
            "ban_reason" => "varchar(255) null default null",
        ]);

        $this->createIndex(User::tableName() . "_email", User::tableName(), "email", true);
        $this->createIndex(User::tableName() . "_username", User::tableName(), "username", true);
        $this->createIndex(User::tableName() . "_phone", User::tableName(), "username", true);

        $this->createTable(UserKey::tableName(),[
            "id" => Schema::TYPE_INTEGER . " not null auto_increment primary key",
            "user_id" => Schema::TYPE_INTEGER . " not null",
            "type" => "tinyint not null",
            "key_value" => "varchar(255) not null",
            "created_at" => Schema::TYPE_INTEGER . " null default null",
            "updated_at" => Schema::TYPE_INTEGER . " null default null",
            "consume_to" => Schema::TYPE_INTEGER . " null default null",
        ]);

        $this->createIndex(UserKey::tableName() . "_user_id", UserKey::tableName(), "user_id", true);
    }

    public function safeDown()
    {
        $this->dropTable(UserKey::tableName());
        $this->dropTable(User::tableName());
        echo "m140908_144113_Init_DB has been returned.\n";
    }
}
