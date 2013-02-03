<?php
/**
 * ActionMetadata extracting metada data of one action
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
class ActionMetadata {
    
    private $__actionReflected;

    private $__controller;

    public function __construct($actionReflected, $controller) {
        $this->__actionReflected = $actionReflected;
        $this->__controller = $controller;
    }

    public function getParams() {
        $params = $this->__actionReflected->getParameters();

        return array_map(function ($param) {
            return $param->getName();
        }, $params);
    }

    public function getParamsNormalized() {
        $params = $this->getParams();

        return array_map(
            function ($param) {
                return sprintf('{%s}', $param);
            }, 
            $params
        );
    }

    public function getController() {
        return $this->__controller;
    }

    public function __call($methodName, array $args) {
        if (method_exists($this->__actionReflected, $methodName)) {
            return call_user_func_array(
                array(
                    $this->__actionReflected,
                    $methodName
                ), 
                $args
            );
        }
 
        throw new RunTimeException(
            sprintf('There is no method with the name %s to call', $methodName)
        );
    }

}