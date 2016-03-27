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
 * @version $Id: CssClassNames.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The CssClassNames sets the CSS-class name through which the style of a {@link Table}
 * can be customized.
 *
 * @package view
 * @subpackage options
 */
class CssClassNames extends OptionStorage {

    /**
     * Sets the CSS-class that will be used for the table's header.
     *
     * @param string $class
     *              The table's header CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setHeaderRow($class) {
        $this->setOption("headerRow", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's data rows.
     *
     * @param string $class
     *              The table's rows CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setTableRow($class) {
        $this->setOption("tableRow ", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's odd data rows.
     *
     * This is only valid if {@Table:setAlternatingRowStyle()} is set to true.
     *
     * @param string $class
     *              The table's odd rows CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setOddTableRow($class) {
        $this->setOption("oddTableRow", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's selected rows.
     *
     * @param string $class
     *              The table's selected rows CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setSelectedTableRow($class) {
        $this->setOption("selectedTableRow", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's hovered rows.
     *
     * @param string $class
     *              The table's hovered rows CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setHoverTableRow($class) {
        $this->setOption("hoverTableRow", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's header cells.
     *
     * @param string $class
     *              The table's header cells CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setHeaderCell($class) {
        $this->setOption("headerCell", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's data cells.
     *
     * @param string $class
     *              The table's data cells CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setTableCell($class) {
        $this->setOption("tableCell", $class);
    }

    /**
     * Sets the CSS-class that will be used for the table's number cells.
     *
     * @param string $class
     *              The table's number cells CSS-class. If set to null, the default
     *              style will be used.
     */
    public function setRowNumberCell($class) {
        $this->setOption("rowNumberCell", $class);
    }

}
?>
