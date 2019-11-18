<?php

namespace Solcre\SolcreFramework2\Common;

use Solcre\SolcreFramework2\Service\IdentityService;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use function is_array;

class BaseControllerRpc extends AbstractActionController
{
    protected $identityService;

    public function __construct(IdentityService $identityService = null)
    {
        $this->identityService = $identityService;
    }

    protected function setUserLogged(): void
    {
        if ($this->identityService instanceof IdentityService) {
            $this->identityService->setIdentity($this->getLoggedUserId());
        }
    }

    protected function getLoggedUserId()
    {
        $identity = $this->getIdentity();
        if ($identity instanceof AuthenticatedIdentity) {
            $identityData = $identity->getAuthenticationIdentity();
            if (is_array($identityData) && array_key_exists('user_id', $identityData)) {
                return $identityData['user_id'];
            }
        }

        return null;
    }

    protected function getParamFromRoute($paramName)
    {
        return $this->params()->fromRoute($paramName);
    }

    protected function getParamFromQueryParams($paramName)
    {
        return $this->queryParams()[$paramName] ?? null;
    }

    protected function getParamFromBodyParams($paramName)
    {
        return $this->bodyParams()[$paramName] ?? null;
    }
}
