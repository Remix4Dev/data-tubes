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
 * @version $Id: ChartArea.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The ChartArea defines the size and position of the displayed chart area.
 *
 * @package view
 * @subpackage options
 */
class ChartArea extends OptionStorage {

    /**
     * Creates a new ChartArea option-set.
     *
     * An integer or a string that contains a number followed by a percent sign
     * can be assigned to each argument. An integer or a number-only string is
     * interpreted as pixel value, a number followed by a percent sign as
     * percentage value.
     *
     * @param mixed $left
     *              The chart's distance from its left border.
     * @param mixed $top
     *              The chart's distance from its upper border.
     * @param mixed $width
     *              The chart's width.
     * @param mixed $height
     *              The chart's height.
     */
    public function __construct($left = null, $top = null, $width = null, $height = null) {
        $this->setOption("left", $left);
        $this->setOption("top", $top);
        $this->setOption("width", $width);
        $this->setOption("height", $height);
    }

    /**
     * Sets the chart's position.
     *
     * An integer or a string that contains a number followed by a percent sign
     * can be assigned to the arguments. An integer or a number-only string is
     * interpreted as pixel value, a number followed by a percent sign as
     * percentage value.
     *
     * @param mixed $left
     *              The chart's distance from its left border. If set to null,
     *              the default distance will be used.
     * @param mixed $top
     *              The chart's distance from its upper border. If set to null,
     *              the default distance will be used.
     */
    public function setPosition($left = null, $top = null) {
        $this->setOption("left", $left);
        $this->setOption("top", $top);
    }

    /**
     * Sets the chart's size.
     *
     * An integer or a string that contains a number followed by a percent sign
     * can be assigned to the arguments. An integer or a number-only string is
     * interpreted as pixel value, a number followed by a percent sign as
     * percentage value.
     *
     * @param mixed $width
     *              The chart's width. If set to null, the default width will be
     *              used.
     * @param mixed $height
     *              The chart's height. If set to null, the default width will be
     *              used.
     */
    public function setSize($width = null, $height = null) {
        $this->setOption("width", $width);
        $this->setOption("height", $height);
    }

}

?>
