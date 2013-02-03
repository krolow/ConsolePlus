<?php
/**
 * ControllerCollection handle the controllers that application have
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

App::uses('App', 'Core');
App::uses('ControllerMetadata', 'ConsolePlus.Lib');

class ControllerCollection {

    private $__blackList = array(
        'AppController',
    );

    public function setBlackList($blackList) {
        $this->__blackList = $blackList;
    }

    public function getBlacklist() {
        return $this->__blackList;
    }

    public function get($plugin = true) {
        $controllers = $this->getMetadata(
            $this->filterControllers(
                App::objects('controller')
            )
        );

        if ($plugin) {
            $plugins = App::objects('plugin');

            foreach ($plugins as $plugin) {
                $controllers = array_merge(
                    $controllers,
                    $this->getMetadata(
                        $this->filterControllers(
                            App::objects($plugin . '.controller')
                        ),
                        $plugin
                    )
                );
            }
        }

        return $controllers;
    }

    public function filterControllers($controllers) {
        $blackList = $this->__blackList;

        return array_filter($controllers, function ($controller) use ($blackList) {
            $matches = array();
            preg_match('/' . implode('|', $blackList) . '/', $controller, $matches);
            
            return count($matches) === 0;
        });
    }

    public function getMetadata($controller, $plugin = null) {
        if (!is_array($controller)) {
            return new ControllerMetadata($controller, $plugin);
        }

        $metadata = array();
        foreach ($controller as $class) {
            array_push(
                $metadata,
                new ControllerMetadata($class, $plugin)
            );
        }

        return $metadata;
    }

}