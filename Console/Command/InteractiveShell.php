<?php
/**
 * InteractiveShell command to iteract with Boris REPL
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
App::uses('ClassRegistry', 'Utility');

require_once APP . 'Plugin' . DS . 'ConsolePlus' . DS . 'Vendor' . DS . 'boris' . DS . 'lib' . DS . 'autoload.php';

class InteractiveShell extends AppShell {
	
	public function main() {
		$boris = new \Boris\Boris('cakephp>');
		$boris->start();
	}

}