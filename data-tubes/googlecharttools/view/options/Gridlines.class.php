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
 * @version $Id: Gridlines.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Gridlines sets the gridlines used in the chart area.
 *
 * @package view
 * @subpackage options
 */
class Gridlines extends OptionStorage {

    /**
     * Creates a new Gridlines option-set.
     *
     * @param string $color
     *              The gridlines' color. Any valid HTML/CSS color definition
     *              (e. g. "white", "#FFF", "#123456").
     * @param int $count
     *              The number of gridlines that will be displayed. Must be 2 or
     *              more.
     */
    public function __construct($color = null, $count = null) {
        $this->setOption("color", $color);
        $this->setCount($count);
    }

    /**
     * Sets the gridlines' color.
     *
     * Any valid HTML/CSS color definition (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              The gridlines' color. If set to null, the default color
     *              will be used.
     */
    public function setColor($color) {
        $this->setOption("color", $color);
    }

    /**
     * Set the number of gridline's that will be displayed.
     *
     * @param int $count
     *              The number of gridlines. Must be 2 or more. If set to null,
     *              the default number will be used.
     */
    public function setCount($count) {
        if ($count >= 2 || $count == null) {
            $this->setOptionNumeric("count", $count);
        }
    }

}

?>
