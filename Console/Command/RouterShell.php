<?php
/**
 * RouteShell command list all routes of app in one table at console
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
 * @package       ConsolePlus.Console.Command
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppShell', 'Console/Command');
App::uses('ControllerCollection', 'ConsolePlus.Lib');
App::uses('RouterInfo', 'ConsolePlus.Lib');
App::uses('ConsoleTable', 'ConsolePlus.Console');

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
        $controllers = $controllerCollection->get();
        
        $routerInfo = new RouterInfo();

        $rows = array();
        foreach ($controllers as $controller) {
            $rows = array_merge($rows, $routerInfo->getControllerRoutes($controller));
        }
        $consoleTable = new ConsoleTable(array('Controller::action', 'Method', 'Route'));
        $consoleTable->setLegend('List of Routes');
        $consoleTable->addRows($rows);
        $this->out($consoleTable->show());
    }

}