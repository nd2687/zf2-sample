<?php
namespace Member\Model;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

class Auth
{
    /** @var ServiceLocator $service_locator */
    protected $service_locator;
    /** @var MemberTable $memberTable */
    protected $memberTable;
    /** @var AuthenticationService $auth */
    protected $auth;

    /** @param ServiceLocator $service_locator */
    public function __construct($service_locator)
    {
        $this->service_locator = $service_locator;
        $this->auth = new AuthenticationService(new SessionStorage('member_session'));
    }

    /**
     * @param String $key ログインID
     * @param String $pass パスワード
     * @return bool
     */
    public function login($key, $pass)
    {
        if (empty($key) && empty($pass)) {
            return false;
        }
        $dbAdapter = $this->service_locator->get('Zend\Db\Adapter\Adapter');
        $authAdapter = new AuthAdapter($dbAdapter);
        $authAdapter
            ->setTableName('member')
            ->setIdentityColumn('login_id')
            ->setCredentialColumn('password');
        $authAdapter
            ->setIdentity($key)
            ->setCredential($pass);
        $result = $this->auth->authenticate($authAdapter);
        if ($result->isValid()){
            $storage = $this->auth->getStorage();
            $storage->write($authAdapter->getResultRowObject());
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $this->auth->getStorage()->clear();
        $this->auth->clearIdentity();
    }

    /**
     * @return bool
     */
    public function checkLogin()
    {
        if ($this->auth->hasIdentity()) {
            return true;
        }
        return false;
    }

    /**
     * @return stdClass|bool
     */
    public function getLoginUser()
    {
        if ($this->auth->hasIdentity()) {
            $identity = $this->auth->getIdentity();
            return $identity;
        }
        return false;
    }
}
