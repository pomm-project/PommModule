<?php

namespace PommModule\Service\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Pomm\Service as PommService;

class PommAuthenticationAdapter implements AdapterInterface, ServiceLocatorAwareInterface 
{
    protected $identity;
    protected $password;
    protected $services;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        $pommService = $this->services->get('PommModule\Service\PommServiceFactory');
        $connection = $pommService->getDatabase('con1')->getConnection();

        // Check credentials in database
        $userMap = $connection->getMapFor('PstudioDb1\JitbCommon\Users');
        $user = $userMap->checkByLoginAndPassword($this->identity, $this->password);

        if (is_null($user)) {
            $code = AuthenticationResult::FAILURE;
            $identity = null;
            $messages[] = "Authentication failed";
        } else {
            $code = AuthenticationResult::SUCCESS;
            $identity = $user;
            $messages[] = "";
        }

        return $this->getResult($code, $identity, $messages);
    }

    public function setIdentityValue($identity)
    {
        $this->identity = $identity;
    }

    public  function setCredentialValue($password)
    {
        $this->password = $password;
    }

    private function getResult($code, $identity, $messages)
    {
        return new AuthenticationResult($code, $identity, $messages);
    }
}
