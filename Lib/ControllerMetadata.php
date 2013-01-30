<?php
App::uses('App', 'Core');

class ControllerMetadata {

    private $__controller;

    private $__plugin;

    private $__reflection;

    private $__blackList = array(
        'beforeFilter',
        'beforeRender',
        'afterFilter',
        'afterRender'
    );

    public function __construct($controller, $plugin = null) {
        $this->__controller = $controller;
        $this->__plugin = $plugin;
    }

    public function getPluginName() {
        return $this->__plugin;
    }

    public function getName() {
        return $this->__controller;
    }

    public function getMethods() {
        if (!$this->__reflection) {
            $this->__reflectClass();
        }

        return $this->filterMethods($this->__reflection->getMethods());
    }

    public function getActionParams($action) {
        $params = array();
        foreach ($action->getParameters() as $param) {
            array_push(
                $params,
                $param->getName()
            );
        }

        return $params;
    }

    public function getActions() {
        return $this->filterMethods($this->getMethods(), true);
    }

    public function filterMethods($methods, $justAction = false) {
        if (!is_array($methods)) {
            InvalidArgumentException('The methods given must be one array');
        }

        $valids = array();
        foreach ($methods as $method) {
            if (
                ($method->getDeclaringClass()->getName() != $this->__controller) ||
                (
                    $justAction == true && 
                    (
                        in_array($method->getName(), $this->__blackList) ||
                        $this->isPrivate($method)
                    )
                )
            ) {
                continue;
            }
            array_push(
                $valids,
                $method
            );
        }

        return $valids;
    }

    public function isPrivate($method) {
        if ($method->isPrivate() || substr($method->getName(), 0, 1) == '_') {
            return true;
        }

        return false;
    }


    private function __reflectClass() {
        if (!class_exists($this->__controller)) {
            App::uses($this->__controller, $this->__getNamespace());
        }
        $this->__reflection = new ReflectionClass($this->__controller);
    }

    private function __getNamespace() {
        $namespace = 'Controller';

        if ($this->__plugin) {
            return $this->__plugin . '.' . $namespace;
        }

        return $namespace;
    }
    
}