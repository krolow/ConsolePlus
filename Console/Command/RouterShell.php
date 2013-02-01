<?php
App::uses('AppShell', 'Console/Command');
App::uses('Router', 'Routing');
App::uses('ControllerCollection', 'ConsolePlus.Lib');

class RouterShell extends AppShell {
    
    protected $_httpMethods = array(
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'HEAD',
        'OPTIONS'
    );

    public function main() {
        $controllerCollection = new ControllerCollection();
        $controllers = ($controllerCollection->get());
        
        config('routes');

        $rows = array();
        foreach ($controllers as $controller) {
            foreach ($controller->getActions() as $action) {
                $rows = array_merge(
                    $rows,
                    $this->_getInformationOfAction($controller, $action)
                );
            }
        }
        var_dump($rows);
    }

    protected function _getInformationOfAction($controller, $action) {
        $label = $this->_getLabel($controller, $action);
        $routes = $this->_getListOfRoutes($controller, $action);

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

    protected function _getListOfRoutes($controller, $action) {
        $resources = Router::mapResources(array());

        if (!in_array($controller->getResourceName(), $resources) && !in_array($controller->getNameNormalized(), $resources)) {
            return array(
                $this->_getURL($controller, $action) => 'ANY',
            );
        }

        foreach ($this->_httpMethods as $method) {
            $url = $this->_getURL($controller, $action, $method);

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

    protected function _getLabel($controller, $action) {
        $paramName = '';
        $params = $action->getParams();

        if (count($params) > 0) {
            $paramName = '$' . implode($params, ', $');
        }

        return sprintf(
            '%s::%s(%s)',
            $controller->getName(),
            $action->getName(),
            $paramName
        );
    }

    protected function _getURL($controller, $action, $method = null) {
        $urlParams = array(
            'controller' => $controller->getNameNormalized(),
            'action' => $action->getName(),
            'plugin' => $controller->getPluginNameNormalized(),
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