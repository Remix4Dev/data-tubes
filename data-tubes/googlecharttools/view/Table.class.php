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
 * @version $Id: Table.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\view\options\CssClassNames;

/**
 * Creates a table.
 *
 * <b>Data format:</b><br />
 * The {@link DataTable} is directly mapped to the table. The DataTable's {@link Column}s
 * are used for the table's header.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/table}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class Table extends Chart {

    /**
     * No navigation/paging buttons will be displayed and no action will be taken when
     * clicking on the header.
     */
    const MODE_DISABLE = "disable";

    /**
     * Forward and backward buttons will be displayed and the table will be sorted
     * by clicking on a header.
     */
    const MODE_ENABLE = "enable";

    /**
     * Clicking on the navigation buttons or header won't change the table's state but fire
     * an event.
     */
    const MODE_EVENT = "event";

    public function getPackage() {
        return "table";
    }

    /**
     * Sets if HTML in cells should be rendered.
     *
     * @param boolean $html
     *              If set to true, HTML is rendered inside the cells.
     *              If set to false or null, no rendering is done.
     */
    public function setAllowHtml($html) {
        $this->setOptionBoolean("allowHtml", $html);
    }

    /**
     * Sets if the row's color should be alternating.
     *
     * @param boolean $alternating
     *              If set to true or null, the color will be alternating.
     */
    public function setAlternatingRowStyle($alternating) {
        $this->setOptionBoolean("alternatingRowStyle", $alternating);
    }

    /**
     * Sets the CSS-classes that can be used to customized the chart's style.
     *
     * @param CssClassNames $classes
     *              The CSS-classes for the table. If set to null, the default
     *              style will be used.
     */
    public function setCssClassNames(CssClassNames $classes) {
        $this->setOption("cssClassNames", $classes);
    }

    /**
     * Sets the table's first row that will be displayed.
     *
     * @param int $number
     *              The first row. If set to null, the first row in the {@link DataTable}
     *              will be used.
     */
    public function setFirstRowNumber($number) {
        $this->setOptionNumeric("firstRowNumber", $number);
    }

    /**
     * Sets the paging mode.
     *
     * @param type $page
     *              The paging mode. Must be one of the <i>MODE_...</i>
     *              constants or null.
     * @throws \InvalidArgumentException
     *              Thrown, if the given page mode is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setPage($page) {
        if ($page != self::MODE_DISABLE && $page != self::MODE_ENABLE &&
                $page != self::MODE_EVENT && $page != null) {
            throw new \InvalidArgumentException("Argument \"page\" is invalid");
        }
        $this->setOption("page", $page);
    }

    /**
     * Sets the number of rows that will be displayed on a page.
     *
     * @param int $size
     *              Number of rows per page.
     */
    public function setPageSize($size) {
        if ($size >= 1 || $size == null) {
            $this->setOptionNumeric("pageSize", $size);
        }
    }

    /**
     * Sets if the columns order should be inverted.
     *
     * That is, the columns in the table will be in inverse order.
     *
     * @param boolean $rtl
     *              If set to true, the order is inverted.
     *              If set to false or null, the same order as in the
     *              {@link DataTable} is used.
     */
    public function setRtlTable($rtl) {
        $this->setOptionBoolean("rtlTable", $rtl);
    }

    /**
     * Sets the horizontal scrolling position.
     *
     * @param int $position
     *              The horizontal position in pixels. If set to null, "0" will
     *              be used.
     */
    public function setScrollLeftStartPosition($position) {
        if ($position >= 0 || $position == null) {
            $this->setOptionNumeric("scrollLeftStartPosition", $position);
        }
    }

    /**
     * Set if the row's number should be displayed in a seperate column.
     *
     * @param boolean $show
     *              If set to true, the numbers will be showed.
     *              If set to false or null, no numbers are visible.
     */
    public function setShowRowNumber($show) {
        $this->setOptionBoolean("showRowNumber", $show);
    }

    /**
     * Sets the sort mode.
     *
     * @param type $sort
     *              The sorting mode. Must be one of the <i>MODE_...</i>
     *              constants or null.
     * @throws \InvalidArgumentException
     *              Thrown, if the given sorting mode is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setSort($sort) {
        if ($sort != self::MODE_DISABLE && $sort != self::MODE_ENABLE &&
                $sort != self::MODE_EVENT && $sort != null) {
            throw new \InvalidArgumentException("Argument \"sort\" is invalid");
        }
        $this->setOption("sort", $sort);
    }

    /**
     * Sets the inital sorting order.
     *
     * @param boolean $sort
     *              If set to true or null, the data is sorted ascending initially.
     */
    public function setSortAscending($sort) {
        $this->setOptionBoolean("sortAscending", $sort);
    }

    /**
     * Sets the initial sorting column.
     *
     * @param int $column
     *              The number of the column by which the table will be sorted initially.
     *              If set to null, no sorting is done.
     */
    public function setSortColumn($column) {
        $this->setOptionNumeric("sortColumn", $column);
    }

    /**
     * Sets the first page that will be set initially.
     *
     * This works only if {@link setPage()} is set to {@link MODE_ENABLE} or {@link MODE_EVENT}.
     *
     * @param int $page
     *              The page that will be set initially. If set to null, the
     *              first page will be set.
     */
    public function setStartPage($page) {
        if ($page >= 0 || $page == null) {
            $this->setOptionNumeric("startPage", $page);
        }
    }

}

?>
