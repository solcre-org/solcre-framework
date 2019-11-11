<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Solcre\SolcreFramework2\Common;

use Exception;
use Solcre\SolcreFramework2\Exception\BaseException;
use Solcre\SolcreFramework2\Service\BaseService;
use Zend\Router\RouteMatch;
use Zend\Stdlib\Parameters;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

class BaseResource extends AbstractResourceListener
{
    const PERMISSION_NAME = 'permission-undefined';
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
            $page = $request->getQuery('page', 1);
            $pageSize = $request->getQuery('size', 25);
            $this->service->setCurrentPage($page);
            $this->service->setItemsCountPerPage($pageSize);

            // Remove size parameter
            $event->getQueryParams()->offsetUnset('size');

            //Normal flow
            return parent::dispatch($event);
        } catch (Exception $exc) {
            $code = $exc->getCode() ? $exc->getCode() : 404;
            return new ApiProblem($code, $exc->getMessage());
        }
    }

    public function checkPermission($event, $permissionName = null, $throwExceptions = true)
    {
        $permissionName = empty($permissionName) ? $this->getPermissionName() : $permissionName;
        if ($permissionName === 'permission-undefined') {
            throw new BaseException('Permission name not defined', 400);
        }

        $loggedUserId = $this->getLoggedUserId($event);
        if (empty($loggedUserId)) {
            //local access
            return true;
        }

        $access = $this->permissionService->checkPermission($event->getName(), $loggedUserId, $permissionName);
        if (! $access && $throwExceptions) {
            throw new BaseException('Method not allowed for current user', 400);
        }
        return $access;
    }

    public function getPermissionName(): string
    {
        return self::PERMISSION_NAME;
    }

    public function getLoggedUserId($event = null)
    {
        $identity = $this->getIdentity();
        if (! empty($event)) {
            $identity = $event->getIdentity();
        }

        $identityData = $identity->getAuthenticationIdentity();
        return $identityData['user_id'];
    }

    protected function normalizeQueryParams(ResourceEvent &$event = null): void
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
            }

            $event->setQueryParams($queryParams);
        }
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
