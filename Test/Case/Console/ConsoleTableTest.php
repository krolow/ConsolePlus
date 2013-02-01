<?php
App::uses('ConsoleTable', 'ConsolePlus.Console');

class ConsoleTableTest extends CakeTestCase {

    public function testSetHeaders() {
        $consoleTable = new ConsoleTable();
        $consoleTable->setHeaders(
            array(
                'Column 1',
                'Column 2',
                'Column 3',
                'Column 4'
            )
        );
        $expected = array(
            'Column 1',
            'Column 2',
            'Column 3',
            'Column 4'
        );
        $this->assertEqual($expected, $consoleTable->getHeaders());
        $this->assertEqual(4, $consoleTable->getNumberOfColumns());
    }

    public function testAddRows() {
        $consoleTable = new ConsoleTable(array('Column 1', 'Column 2'));
        $expected = array(
            'Column 1',
            'Column 2',
        );
        $this->assertEqual($expected, $consoleTable->getHeaders());

        $consoleTable->addRows(
            array(
                array(
                    'cc1',
                    'cc2'
                ),
                array(
                    'cc3',
                    'cc4'
                )
            )
        );
        $this->assertEqual(2, $consoleTable->getNumberOfColumns());
        $this->assertEqual(3, $consoleTable->getNumberOfRows());
    }

    public function testShow() {
        $consoleTable = new ConsoleTable(array('Column 1', 'Column 2'));
        $consoleTable->addRows(
            array(
                array(
                    'cc1',
                    'cc2'
                ),
                array(
                    'cc3',
                    'cc4'
                ),
                array(
                    'that is the biggest for this column',
                    'thatnot'
                ),
            )
        );
        $expected = strlen('that is the biggest for this column');
        $this->assertEqual($expected, $consoleTable->getColumnSize(1));
        $expected = strlen('Column 2');
        $this->assertEqual($expected, $consoleTable->getColumnSize(2));
    }
    
}