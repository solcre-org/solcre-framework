<?php

namespace Solcre\SolcreFramework2\Common;

use MegaPharma\V1\Domain\Common\Service\IdentityService;
use Zend\Mvc\Controller\AbstractActionController;

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
        $identityData = $identity->getAuthenticationIdentity();
        return $identityData['user_id'];
    }

    protected function getParamFromRoute($paramName)
    {
        return $this->params()->fromRoute($paramName) ?? null;
        ;
    }

    protected function getParamFromQueryParams($paramName)
    {
        return $this->queryParams()[$paramName] ?? null;
    }

    protected function getParamFromBodyParams($paramName)
    {
        return $this->bodyParams()[$paramName] ?? null;
        ;
    }
}
