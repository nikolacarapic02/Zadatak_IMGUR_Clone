<?php

namespace app\models;

use app\core\UserModel;

class User extends UserModel
{
    protected string $id = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public static function tableName(): string
    {
        return 'user';
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function register()
    {
        return $this->save();
    }

    public function attributes(): array
    {
        return ['username', 'email', 'password'];
    }

    public function rules(): array
    {
        return [
            'username' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 50]],
            'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function labels(): array
    {
        return [
            'username' => 'Username',
            'email' => 'Email address',
            'password' => 'Password',
            'passwordConfirm' => 'Confirm password'
        ];
    }

    public function getDisplayName(): string
    {
        return $this->username;
    }
}