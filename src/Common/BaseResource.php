<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Solcre\SolcreFramework2\Common;

use Exception;
use Solcre\SolcreFramework2\Service\BaseService;
use Solcre\Lms\Service\PermissionService;
use Zend\Stdlib\Parameters;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;

class BaseResource extends AbstractResourceListener
{

    /**
     *
     * @var BaseService
     */
    protected $service;
    protected $permissionService;
    const PERMISSION_NAME = 'permission-undefined';

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
            $data = (array)$event->getParam('data', array());
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

    public function getLoggedUserId($event = null)
    {
        if (!empty($event)) {
            $identity = $event->getIdentity();
        } else {
            $identity = $this->getIdentity();
        }
        $identityData = $identity->getAuthenticationIdentity();
        return $identityData['user_id'];
    }

    public function checkPermission($event, $permissionName = null, $throwExceptions = true)
    {
        $permissionName = empty($permissionName) ? $this->getPermissionName() : $permissionName;
        if ($permissionName == "permission-undefined") {
            throw new Exception("Permission name not defined", 400);
        }
        
        $loggedUserId = $this->getLoggedUserId($event);
        if(empty($loggedUserId)){
            //local access
            return true;
        }

        $access = $this->permissionService->checkPermission($event->getName(), $loggedUserId, $permissionName);
        if (!$access && $throwExceptions) {
            throw new Exception('Method not allowed for current user', 400);
        }
        return $access;
    }

    public function getPermissionName()
    {
        return self::PERMISSION_NAME;
    }
    
    protected function normalizeQueryParams(ResourceEvent &$event = null){
        if(empty($event)){
            return;
        }
        
        //Get event query params
        $queryParams = $event->getQueryParams() ?: [];
        /* @var $queryParams Parameters */
                
        //Normalize
        if(($queryParams instanceof Parameters) && $queryParams->count() > 0){
            //Array for iteration
            $qp = $queryParams->toArray();            
            
            //For each qp
            foreach($qp as $key => $value) {
                //Check now()
                if($value === "now()"){
                    $queryParams->set($key, date("Y-m-d"));
                }
            }
        }
        
        //Set query params to event
        $event->setQueryParams($queryParams);
    }

    public function getUriParam($key)
    {
        $e = $this->getEvent();
        $route = $e->getRouteMatch();
        if (!empty($e) && !empty($route)) {
            return $route->getParam($key);
        } else {
            throw new Exception("Event or Route not found");
        }

    }
}