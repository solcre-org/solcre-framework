<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Solcre\SolcreFramework2\Common;

use Exception;
use InvalidArgumentException;
use MegaPharma\V1\Domain\Common\Exception\BaseException;
use MegaPharma\V1\Domain\Common\Service\IdentityService;
use MegaPharma\V1\Domain\User\Service\PermissionService;
use RuntimeException;
use Solcre\SolcreFramework2\Service\BaseService;
use Zend\Router\Http\RouteMatch;
use Zend\Stdlib\Parameters;
use ZF\ApiProblem\ApiProblem;
use ZF\ContentNegotiation\Request;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

class BaseResource extends AbstractResourceListener
{
    private const PERMISSION_NAME = 'permission-undefined';
    /**
     *
     * @var BaseService
     */
    protected $service;
    protected $permissionService;

    public function __construct(BaseService $service, PermissionService $permissionService)
    {
        $this->service = $service;
        $this->permissionService = $permissionService;
    }

    public function dispatch(ResourceEvent $event)
    {
        try {
            //Set event and check permissions
            $this->event = $event;
            $this->checkPermission($event);

            //Normalized as array query params and adata
            $this->normalizeQueryParams($event);
            $data = (array)$event->getParam('data', []);
            $event->setParam('data', $data);

            //Set page and size to the service
            $request = $event->getRequest();
            if ($request instanceof Request) {
                $page = $request->getQuery('page', 1);
                $pageSize = $request->getQuery('size', 50);
                $this->service->setCurrentPage($page);
                $this->service->setItemsCountPerPage($pageSize);
            }

            $identityService = $this->service->getIdentityService();
            if ($identityService instanceof IdentityService) {
                $identityService->setIdentity($this->getLoggedUserId());
            }
            //Normal flow
            return parent::dispatch($event);
        } catch (BaseException $exc) {
            return new ApiProblem($exc->getCode(), $exc->getMessage(), null, null, $exc->getAdditional());
        } catch (Exception $exc) {
            $code = $exc->getCode() ?? 404;
            return new ApiProblem($code, $exc->getMessage());
        }
    }

    public function checkPermission($event, $permissionName = null, $throwExceptions = true): bool
    {
        $permissionName = empty($permissionName) ? $this->getPermissionName() : $permissionName;
        if ($permissionName === 'permission-undefined') {
            throw new InvalidArgumentException('Permission name not defined', 400);
        }

        $loggedUserId = $this->getLoggedUserId($event);

        if (empty($loggedUserId)) {
            //local access
            return true;
        }

        $access = $this->permissionService->checkPermission($event->getName(), $loggedUserId, $permissionName);
        if (! $access && $throwExceptions) {
            throw new BaseException(
                'Method not allowed for current user',
                400,
                [
                    'error_code' => 'METHOD_NOT_ALLOWED'
                ]
            );
        }
        return $access;
    }

    public function getPermissionName()
    {
        return self::PERMISSION_NAME;
    }

    public function getLoggedUserId($event = null)
    {
        if ($event instanceof ResourceEvent) {
            $identity = $event->getIdentity();
        } else {
            $identity = $this->getIdentity();
        }
        if ($identity instanceof IdentityInterface) {
            $identityData = $identity->getAuthenticationIdentity();
            return $identityData['user_id'];
        }
        return null;
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
                    $queryParams->set($key, date('Y-m-d'));
                }

                if ($value === 'null') {
                    $queryParams->set($key, null);
                }
            }
        }

        //Set query params to event
        $event->setQueryParams($queryParams);
    }

    public function getUriParam($key): int
    {
        $e = $this->getEvent();
        $route = $e->getRouteMatch();
        if ($route instanceof RouteMatch) {
            return (int)$route->getParam($key);
        }
        throw new RuntimeException('Event or Route not found');
    }
}
