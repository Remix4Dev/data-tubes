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
 * @version $Id: DataTable.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package model
 */
namespace googlecharttools\model;

/**
 * A DataTable represents the data that will be visualized in a chart.
 *
 * Google Chart Tools uses a table-format as data-storage. The actual function
 * of the table's head and cells depends on the chart the data is used for (e. g. in
 * pie-charts, a table with two rows is used where the first column represents
 * the slices' label. The second column is used as the slices' value).
 *
 * The DataTable is divided into a column-definition (the table's header) and
 * rows. By the column definition, the column's value-type (string, number, etc.)
 * is specified and the data (that will be visualized) is stored in the rows.
 *
 * <b>Example:</b>
 * Lets assume the table below should be stored in a DataTable.
 *
 * <pre>
 * |----------------|-----------------------|
 * | Name: Task     | Name: Hours per Day   |
 * | Type: string   | Type: Number          |
 * |----------------|-----------------------|
 * |    Work        |   11                  |
 * |    Eat         |   2                   |
 * |    Commute     |   2                   |
 * |    Watch TV    |   2                   |
 * |    Sleep       |   7                   |
 * |----------------|-----------------------|
 * </pre>
 *
 * This table will be represented by the DataTable through the following code:
 * <code>
 * $labelColumn = new Column(Column::STRING, "t", "Task");
 * $valueColumn = new Column(Column::NUMBER, "h", "Hours per Day");
 *
 * $data = new DataTable();
 * $data->addColumn($labelColumn);
 * $data->addColumn($valueColumn);
 *
 * $workRow = new Row();
 * $workRow->addCell(new Cell("Work"));
 * $workRow->addCell(new Cell(11));
 * $data->addRow($workRow);
 *
 * $eatRow = new Row();
 * $eatRow->addCell(new Cell("Eat"));
 * $eatRow->addCell(new Cell(2));
 * $data->addRow($eatRow);
 *
 * $commuteRow = new Row();
 * $commuteRow->addCell(new Cell("Commute"));
 * $commuteRow->addCell(new Cell(2));
 *
 * ...
 * </code>
 *
 * You will find detailed information on how many columns are supported by a specific
 * chart, their purpose etc. in detail at the corresponding chart's subpage
 * published at {@link https://google-developers.appspot.com/chart/interactive/docs/gallery}.
 *
 * @package model
 */
class DataTable {

    /** @var Column[] */
    private $cols = array();

    /** @var Row[] */
    private $rows = array();

    /**
     * Adds a new column definition to the table.
     *
     * @param Column $col
     *              The new column definition.
     * @return DataTable
     *              This object.
     */
    public function addColumn(Column $col) {
        $this->cols[] = $col;
        return $this;
    }

    /**
     * Adds a new data-row to the table.
     *
     * @param Row $row
     *              The new row.
     * @return DataTable
     *              This object.
     * @throws \InvalidArgumentException
     *              Thrown, if the row has more cells than column definitions
     *              have been added with the {@link addColumn()} method.
     */
    public function addRow(Row $row) {
        if ($row->getCellsCount() > $this->getColumnsCount()) {
            throw new \InvalidArgumentException("The DataTable has to have at least the same number of column definitions as cells per row.");
        }
        $this->rows[] = $row;
        return $this;
    }

    /**
     * Fills the DataTable by reading an associative array.
     *
     * Every element of the array will be seen as a new row whereby the
     * array's keys are used for the first column and the array's values
     * for the second column. Thus, this will require a DataTable with two columns
     * only.
     *
     * @param string[] $array
     *              An associative array.
     */
    public function addRowsAssocArray($array) {
        foreach ($array as $key => $value) {
            $row = new Row();
            $row->addCell(new Cell($key));
            $row->addCell(new Cell($value));
            $this->rows[] = $row;
        }
    }

    /**
     * Gets the number of columns that have been added to the DataTable.
     *
     * @return int
     *              Number of columns added with {@link addColumn()}.
     */
    public function getColumnsCount() {
        return count($this->cols);
    }

    /**
     * Gets the number of rows that have been added to the DataTable.
     *
     * @return int
     *              Number of rows added with {@link addRow()}.
     */
    public function getRowsCount() {
        return count($this->rows);
    }

    /**
     * Gets all columns that have been added to the DataTable.
     *
     * @return Column[]
     *              The DataTable's columns.
     */
    public function getColumns() {
        return $this->cols;
    }

    /**
     * Gets all rows that have been added to the DataTable.
     *
     * @return Row[]
     *              The DataTable's rows.
     */
    public function getRows() {
        return $this->rows;
    }

    /**
     * Converts this object into a JSON-object.
     *
     * This representation follows the JavaScript literal format specified
     * at {@link https://google-developers.appspot.com/chart/interactive/docs/reference#dataparam}.
     *
     * @return string
     *              The JSON representation of this DataTable.
     */
    public function toJson() {
        $json = "{\n";

        // Build cols-array
        $json .= "  \"cols\": [\n";
        $first = true;
        foreach ($this->cols as $col) {
            if ($first) {
                $first = false;
            } else {
                $json .= ",\n";
            }
            $json .= "    " . $col->toJson();
        }
        $json .= "],\n";


        // Build rows-array
        $json .= "  \"rows\": [\n";
        $first = true;
        foreach ($this->rows as $row) {
            if ($first) {
                $first = false;
            } else {
                $json .= ",\n";
            }
            $json .= "    " . $row->toJson($this->cols);
        }
        $json .= "]\n";
        $json .= "}";

        return $json;
    }

}

?>