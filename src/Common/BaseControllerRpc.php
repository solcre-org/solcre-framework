<?php

namespace Solcre\SolcreFramework2\Common;

use Psr\Log\LoggerInterface;
use Solcre\SolcreFramework2\Interfaces\IdentityInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity;
use function is_array;

class BaseControllerRpc extends AbstractActionController
{
    protected $identityService;
    protected $logger;

    public function __construct(IdentityInterface $identityService = null, ?LoggerInterface $logger = null)
    {
        $this->identityService = $identityService;
        $this->logger = $logger;
    }

    protected function setUserLogged(): void
    {
        if ($this->identityService instanceof IdentityInterface) {
            $this->identityService->setUserId($this->getLoggedUserId());
            $this->identityService->setOauthType($this->getAuthenticationOauthType());
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

    protected function getAuthenticationOauthType(): ?int
    {
        $authenticationIdentity = $this->getAuthenticationIdentity();
        if ($authenticationIdentity !== null) {
            return $authenticationIdentity['oauth_type'];
        }
        return null;
    }

    protected function getAuthenticationIdentity(): ?array
    {
        $identity = $this->getIdentity();
        return $identity->getAuthenticationIdentity();
    }

    protected function getAuthenticationId(): ?int
    {
        $authenticationIdentity = $this->getAuthenticationIdentity();
        if ($authenticationIdentity !== null) {
            return $authenticationIdentity['user_id'];
        }
        return null;
    }

    public function createApiProblemResponse(int $code, string $message, array $additional = []): ApiProblemResponse
    {
        $apiProblem = $this->createApiProblem($code, $message, $additional);
        return new ApiProblemResponse($apiProblem);
    }

    private function createApiProblem(int $code, string $message, array $additional): ApiProblem
    {
        return new ApiProblem($code, $message, null, null, $additional);
    }
}
