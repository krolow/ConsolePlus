<?php
/**
 * RouterInfo extracts routes information for a given ControllerMetadata
 *
 * PHP 5.3
 *
 * ConsolePlus (https://github.com/krolow/ConsolePlus)
 * Copyright 2013, Vinícius Krolow (http://github.com/krolow)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2013, Vinícius Krolow (http://github.com/krolow)
 * @link          https://github.com/krolow/ConsolePlus Console Plus
 * @package       ConsolePlus.Lib
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('ControllerMetadata', 'ConsolePlus.Lib');
App::uses('ActionMetadata', 'ConsolePlus.Lib');
App::uses('Router', 'Routing');

class RouterInfo {

	protected $_routes;

	protected $_controller;

    protected $_httpMethods = array(
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'HEAD',
        'OPTIONS'
    );

    public function __construct() {
    	config('routes');
    }

	public function getControllerRoutes(ControllerMetadata $controller) {
        $rows = array();

        foreach ($controller->getActions() as $action) {
            $rows = array_merge(
                $rows,
                $this->_getInformationOfAction($action)
            );
        }

        return $rows;
	}

	public function getActionRoutes(ActionMetadata $action) {
		return $this->_getInformationOfAction($action);
	}

    protected function _getInformationOfAction(ActionMetadata $action) {
        $label = $this->_getLabel($action);
        $routes = $this->_getListOfRoutes($action);

        $rows = array();
        foreach ($routes as $route => $methods) {
            $method = $this->_getMethodName($methods);
            array_push(
                $rows,
                array(
                    $label,
                    $method,
                    $route
                )
            );
        }

        return $rows;
    }

    protected function _getMethodName($methods) {
        if (!is_array($methods) || count($methods) == count($this->_httpMethods)) {
            return 'ANY';
        }

        return implode('|', $methods);
    }

    protected function _getListOfRoutes(ActionMetadata $action) {
    	$controller = $action->getController();
        $resources = Router::mapResources(array());

        if (!in_array($controller->getResourceName(), $resources) && !in_array($controller->getNameNormalized(), $resources)) {
            return array(
                $this->_getURL($action) => 'ANY',
            );
        }

        foreach ($this->_httpMethods as $method) {
            $url = $this->_getURL($action, $method);

            if (!isset($routes[$url])) {
                $routes[$url] = array(); 
            }

            array_push(
                $routes[$url],
                $method
            );
        }

        return $routes;
    }

    protected function _getLabel(ActionMetadata $action) {
        $paramName = '';
        $params = $action->getParams();

        if (count($params) > 0) {
            $paramName = '$' . implode($params, ', $');
        }

        return sprintf(
            '%s::%s(%s)',
            $action->getController()->getName(),
            $action->getName(),
            $paramName
        );
    }

    protected function _getURL(ActionMetadata $action, $method = null) {
        $urlParams = array(
            'controller' => $action->getController()->getNameNormalized(),
            'action' => $action->getName(),
            'plugin' => $action->getController()->getPluginNameNormalized(),
        );

        if (!is_null($method)) {
            $urlParams +=  array('[method]' => $method);
        }

        $url = urldecode(Router::url($urlParams + $action->getParamsNormalized()));

        if (!is_null($method) && $pos = strpos($url, '[method]') !== false) {
            return str_replace('[method]:' . $method, '', $url);
        }

        return $url;
    }

}