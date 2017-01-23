<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\components\AuthorizeVerify;
use common\components\XUtils;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
//            dprint($user);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login($time = 14400) {
        if ($this->validate()) {
            AuthorizeVerify::setUser($this->_user, $time);
//            dprint($this->_user);
            $this->_user->last_login_ip = XUtils::getClientIP();
            $this->_user->last_login_time = time();
            $this->_user->login_count = $this->_user->login_count + 1;
            $this->_user->save();

            return true;
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
//            dprint($this->username);
            $this->_user = User::findByUsername($this->username);
        }
//dprint($this->_user);
        return $this->_user;
    }

}
