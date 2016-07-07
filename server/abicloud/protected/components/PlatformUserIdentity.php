<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class PlatformUserIdentity extends CUserIdentity {

    /**
     * @var int
     */
    const Authenticated = 0;

    /**
     * @var int
     */
    const InvalidCredentials = 1;

    /**
     * @var int Our user id
     */
    private $_id;

    /**
     * @var PlatformUser
     */
    protected $_user = null;

    /**
     *
     * @var type auth type
     */
    public $_auth_type = 'KTV';
    
    /**
     * @param PlatformUser $user
     *
     * @return bool
     */
    public function logInUser($user) {
        return $this->_initializeSession($user);
    }

    /**
     * @param PlatformUser $user
     *
     * @return bool
     */
    protected function _initializeSession($user) {
        if (empty($user)) {
            return false;
        }


        $this->_user = $user;
        $this->_id = $user->id;

        $this->setState('username', $user->email);
        $this->setState('password', $user->password);

        $this->setState('lastLogin', date("Y-m-d H:i:s", strtotime($user->last_login_time)));
        $this->setState('role', $user->role);
        $user->saveAttributes(array(
            'last_login_time' => date("Y-m-d H:i:s", time()),
        ));
        $this->errorCode = self::ERROR_NONE;

        return true;
    }

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

        $user = PlatformUser::model()->validateUserByName($this->username, $this->_auth_type);
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$user->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            return $this->_initializeSession($user);
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

    /**
     * @return PlatformUser
     */
    public function getUser() {
        return $this->_user;
    }

}
