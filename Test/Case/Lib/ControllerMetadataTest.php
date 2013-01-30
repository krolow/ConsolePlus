<?php
App::uses('ControllerMetadata', 'ConsolePlus.Lib');
App::uses('Controller', 'Controller');

/**
 * AppController class
 *
 * @package       Cake.Test.Case.Controller
 */
class ControllerTestAppController extends Controller {

/**
 * helpers property
 *
 * @var array
 */
    public $helpers = array('Html');

/**
 * components property
 *
 * @var array
 */
    public $components = array('Cookie');
}


class ContentsController extends ControllerTestAppController {
    
    public function beforeFilter() {

    }

    public function index() {

    }

    public function view($content) {

    }

    public function details($content, $author) {

    }

    private function testing() {

    }

    public function __shouldNotWork() {

    }

    public function should_work() {

    }

}

class ControllerMetadataTest extends CakeTestCase {

    public function testGetMethods() {
        $metadata = new ControllerMetadata('ContentsController');
        $this->assertEquals(7, count($metadata->getMethods()));
    }

    public function testGetActions() {
        $metadata = new ControllerMetadata('ContentsController');
        $this->assertEquals(4, count($metadata->getActions()));        
    }

    public function testIsPrivate() {
        $metadata = new ControllerMetadata('ContentsController');
        $this->assertEquals(false, $metadata->isPrivate(new ReflectionMethod('ContentsController', 'view')));
        $this->assertEquals(true, $metadata->isPrivate(new ReflectionMethod('ContentsController', 'testing')));
        $this->assertEquals(true, $metadata->isPrivate(new ReflectionMethod('ContentsController', '__shouldNotWork')));
        $this->assertEquals(false, $metadata->isPrivate(new ReflectionMethod('ContentsController', 'should_work')));
    }

    public function testGetParams() {
        $metadata = new ControllerMetadata('ContentsController');
        $actions = $metadata->getActions();
        $result = $metadata->getActionParams($actions[0]);
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = $metadata->getActionParams($actions[1]);
        $expected = array('content');
        $this->assertEquals($expected, $result);


        $result = $metadata->getActionParams($actions[2]);
        $expected = array('content', 'author');
        $this->assertEquals($expected, $result);

        $result = $metadata->getActionParams($actions[3]);
        $expected = array();
        $this->assertEquals($expected, $result);
    }
    
}