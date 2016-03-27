<?php

/*
 * Copyright 2012 Patrick Strobel.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Patrick Strobel
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link http://code.google.com/p/googlecharttools-php
 * @version $Id: Row.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package model
 */

namespace googlecharttools\model;

/**
 * A Row represents a data entry (a row) of the {@link DataTable}.
 *
 * Each row contains one or more {@link Cell}s that stores the actual data of
 * this row. The cells of each row have to match the {@link Column} definition.
 * {@link Column} and {@link Cell} are connected by the order they
 * are added to the {@link DataTable} and {@link Row}, respectively.
 * That is, the {@link Cell} added first belongs to the {@link Column} definition
 * that has been added first, the second-added {@link Cell} to the second-added
 * {@link Column}, and so on. Therefore, no more cells per row can be added then
 * columns have been defined.
 *
 *
 * @package model
 */
class Row {

    /** @var Cell[] */
    private $cells = array();

    /**
     * Adds a new cell to this row.
     *
     * @param Cell $cell
     *              A cell.
     * @return Row
     *              This object.
     */
    public function addCell(Cell $cell) {
        $this->cells[] = $cell;
        return $this;
    }

    /**
     * Gets all cells that have been added to this row.
     *
     * @return Cell[]
     *              All cells that have been added to this row so far.
     */
    public function getCells() {
        return $this->cells;
    }

    /**
     * Gets the number of cells that have been added to this row.
     *
     * @return int
     *              Number of cells.
     */
    public function getCellsCount() {
        return count($this->cells);
    }

    /**
     * Converts this object into a JSON-object.
     *
     * This representation follows the JavaScript literal format specified
     * at {@link https://google-developers.appspot.com/chart/interactive/docs/reference#dataparam}
     * as element of the "row" JSON-array.
     *
     * @param Column[] $cols
     *              The {@link DataTable}'s columns (used to convert the
     *              cells' value into the proper output format).
     * @return string
     *              The JSON representation of this Row.
     */
    public function toJson($cols) {
        $string = "{\"c\": [";

        $first = true;
        foreach ($this->cells as $i => $cell) {
            if ($first) {
                $first = false;
            } else {
                $string .= ", ";
            }

            $string .= $cell->toJson($cols[$i]->getType());
        }

        $string .= "]}";
        return $string;
    }

}
?>