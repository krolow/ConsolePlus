<?php
/**
 * CakePHP ConsoleTable it's a helper to output table data
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
 * @package       ConsolePlus.Console
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ConsoleTable {

    const ALIGN_LEFT = 0;

    const ALIGN_RIGHT = 1;

    const ALIGN_CENTER = 2;

    protected $_headers;

    protected $_numberOfColumns = 0;

    protected $_rows = array();

    protected $_sizes = array();

    protected $_legend;

    public function __construct($headers = array(), $border = true) {
        $this->setHeaders($headers);
        $this->_border = true;
    }

    public function setHeaders($headers) {
        $this->_headers = $headers;
        $this->addRow($headers, true);
    }

    public function setLegend($legend) {
        $this->_legend = $legend;
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function addRow($row, $begin = false) {
        if ($begin) {
            array_unshift($this->_rows, $row);
        } else {
            array_push($this->_rows, $row);
        }

        $this->_updateColumns($row);
    }

    public function addRows($rows) {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
    }

    public function getNumberOfRows() {
        return count($this->_rows);
    }

    protected function _updateColumns(array $rows) {
        $row = reset($rows);

        if (is_array($row)) {
            foreach ($rows as $row) {
                $this->_updateColumns($row);
            }
        }

        $count = count($rows);

        if ($count > $this->_numberOfColumns) {
            $this->_numberOfColumns = $count;
        }
    }

    public function show() {
        $this->__calculateSizeOfColumns();

        $lines = array();
        $sizeOfTable = array_sum($this->_sizes) + $this->getNumberOfRows();

        if (!is_null($this->_legend)) {
            $lines[] = $this->__getBorder($sizeOfTable);
            $lines[] = sprintf('|%s|', $this->alignCenter($this->_legend, $sizeOfTable));
        }

        if ($this->_border == true) {
            $lines[] = $this->__getBorder($sizeOfTable);
        }

        foreach ($this->_rows as $row => $columns) {
            $param = '';
            foreach ($columns as $col => $value) {
                $colSize = $this->getColumnSize($col + 1);
                $param .= '| %-' . $colSize . '.' . $colSize . 's ';
            }
            $param .= '|';
            $lines[] = call_user_func_array('sprintf', array_merge(array($param), $columns));

            if ($row == 0 && $this->_border) {
                $lines[] = $this->__getBorder($sizeOfTable);
            }
        }

        if ($this->_border) {
            $lines[] = $this->__getBorder($sizeOfTable);
        }

        return implode($lines, "\r\n");
    }

    public function alignCenter($text, $size) {
        $textSize = strlen($text);
        return sprintf(
            "%s%s%s",
            str_repeat(' ', floor(($size  - $textSize) / 2)),
            $text,
            str_repeat(' ', ceil(($size - $textSize) / 2))
        );
    }

    private function __getBorder($size) {
        return '+' . str_repeat('-', $size) . '+';
    }

    private function __calculateSizeOfColumns() {
        $rows = $this->_rows + $this->_headers;

        foreach ($rows as $rowIndex => $columns) {
            foreach ($columns as $colIndex => $col) {
                $colIndex++;
                if (!isset($this->_sizes[$colIndex])) {
                    $this->_sizes[$colIndex] = 0;
                }
                if (($size = strlen($col)) > $this->_sizes[$colIndex]) {
                    $this->_sizes[$colIndex] = $size;
                }
            }
        }

        return $this->_sizes;
    }

    public function getColumnSize($column) {
        if (count($this->_sizes) === 0) {
            $this->__calculateSizeOfColumns();
        }

        return $this->_sizes[$column];
    }

    public function getNumberOfColumns() {
        return $this->_numberOfColumns;
    }


}