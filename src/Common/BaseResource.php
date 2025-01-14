<?php

namespace Solcre\SolcreFramework2\Common;

use Exception;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\MvcAuth\Identity\IdentityInterface;
use Laminas\ApiTools\MvcAuth\Identity\IdentityInterface as IdentityInterfaceLaminas;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\ApiTools\Rest\ResourceEvent;
use Laminas\EventManager\Event;
use Laminas\Http\Request;
use Laminas\Router\RouteMatch;
use Laminas\Stdlib\Parameters;
use Psr\Log\LoggerInterface;
use Solcre\SolcreFramework2\Exception\BaseException;
use Solcre\SolcreFramework2\Interfaces\LoggerAwareInterface;
use Solcre\SolcreFramework2\Interfaces\PermissionInterface;
use Solcre\SolcreFramework2\Service\BaseService;

class BaseResource extends AbstractResourceListener
{
    public const NO_PERMISSION = 'permission-undefined';

    protected $service;
    protected $permissionService;

    public function __construct(BaseService $service, ?PermissionInterface $permissionService = null)
    {
        $this->service = $service;
        $this->permissionService = $permissionService;
    }

    public function dispatch(ResourceEvent $event)
    {
        try {
            $this->event = $event;

            $this->setIdentity();
            $this->checkPermission($event);

            $request = $event->getRequest();
            if (!$request instanceof Request) {
                throw new BaseException('Request does not exists', 404);
            }

            $method = $request->getMethod();
            if ($method === Request::METHOD_GET) {
                $this->normalizeQueryParams($event);

                $page = $request->getQuery('page', 1);
                $pageSize = $request->getQuery('size', $this->service->getItemsCountPerPage());
                $this->service->setCurrentPage($page);
                $this->service->setItemsCountPerPage($pageSize);

                // Remove size parameter
                $parameters = $event->getQueryParams();
                if ($parameters instanceof Parameters & $parameters->offsetExists('size')) {
                    $parameters->offsetUnset('size');
                }
            } elseif ($method === Request::METHOD_POST || $method === Request::METHOD_PUT || $method === Request::METHOD_PATCH || $method === Request::METHOD_DELETE) {
                $this->normalizeBodyParams($event);
            }

            return parent::dispatch($event);
        } catch (BaseException $baseException) {
            return new ApiProblem($baseException->getCode(), $baseException->getMessage());
        } catch (\Throwable $exc) {
            if ($this->service->logger instanceof LoggerInterface) {
                $this->service->logger->error($exc->getMessage());
            }

            return new ApiProblem($exc->getCode(), 'Ha ocurrido un error inesperado');
        }
    }

    private function setIdentity(): void
    {
        if ($this->service instanceof LoggerAwareInterface) {
            $identityService = $this->service->getIdentityService();
            $identityService->setUserId($this->getLoggedUserId());
            $identityService->setOauthType($this->getLoggedUserOauthType());
        }
    }

    public function getLoggedUserId($event = null)
    {
        $identity = $this->getIdentity();

        if (!empty($event)) {
            $identity = $event->getIdentity();
        }

        $identityData = $identity->getAuthenticationIdentity();

        return $identityData['user_id'] ?? null;
    }

    public function getLoggedUserOauthType(Event $event = null)
    {
        $identity = $this->getLoggedUserIdentity($event);
        if ($identity instanceof IdentityInterface) {
            $identityData = $identity->getAuthenticationIdentity();
            return $identityData['oauth_type'] ?? null;
        }
        return null;
    }

    private function getLoggedUserIdentity(Event $event = null): ?IdentityInterfaceLaminas
    {
        $identity = null;

        if ($event instanceof ResourceEvent) {
            $identity = $event->getIdentity();
        } else {
            $identity = $this->getIdentity();
        }

        return $identity;
    }

    public function checkPermission(ResourceEvent $event, $permissionName = null, $throwExceptions = true): bool
    {
        if (!$this->permissionService instanceof PermissionInterface) {
            return true;
        }

        $permissionName = empty($permissionName) ? $this->getPermissionName() : $permissionName;
        $loggedUserId = $this->getLoggedUserId($event);
        if (empty($permissionName) || $permissionName === self::NO_PERMISSION || $loggedUserId === null) {
            return true;
        }

        $hasPermission = $this->permissionService->hasPermission($event->getName(), $permissionName, $loggedUserId, $this->getLoggedUserOauthType($event));
        if (!$hasPermission && $throwExceptions) {
            $this->permissionService->throwMethodNotAllowedForCurrentUserException();
        }
        return $hasPermission;
    }

    public function getPermissionName(): string
    {
        return self::NO_PERMISSION;
    }

    protected function normalizeQueryParams(ResourceEvent $event = null): void
    {
        if ($event === null) {
            return;
        }

        //Get event query params
        $queryParams = $event->getQueryParams() ?: [];
        /* @var $queryParams Parameters */

        //Normalize
        if (($queryParams instanceof Parameters) && $queryParams->count() > 0) {
            //Array for iteration
            $qp = $queryParams->toArray();

            //For each qp
            foreach ($qp as $key => $value) {
                //Check now()
                if ($value === 'now()') {
                    $queryParams->set($key, date('Y-m-d H:i:s'));
                }

                if ($value === 'null') {
                    $queryParams->set($key, null);
                }
            }

            $event->setQueryParams($queryParams);
        }
    }

    protected function normalizeBodyParams(ResourceEvent $event = null): void
    {
        if ($event === null) {
            return;
        }

        $bodyParams = $event->getParam('data', []);

        if (!empty($bodyParams)) {
            //For each qp
            foreach ($bodyParams as $key => $value) {
                if ($value === 'null') {
                    $bodyParams->$key = null;
                }
            }
        }

        $event->setParam('data', (array)$bodyParams);
    }

    public function getUriParam($key)
    {
        $event = $this->getEvent();

        if ($event instanceof ResourceEvent) {
            $route = $event->getRouteMatch();

            if ($route instanceof RouteMatch) {
                return $route->getParam($key);
            }
        }

        return null;
    }
}
