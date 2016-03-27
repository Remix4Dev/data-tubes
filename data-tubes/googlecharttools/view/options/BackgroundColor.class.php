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
 * @version $Id: BackgroundColor.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The BackgroundColor stores a chart's background color, border-width and
 * border-color
 *
 * @package view
 * @subpackage options
 */
class BackgroundColor extends OptionStorage {

    /**
     * Creates a new BackgroundColor option-set.
     *
     * @param string $stroke
     *              The chart's border color. Any valid HTML/CSS color definition
     *              (e. g. "white", "#FFF", "#123456").
     * @param int $strokeWidth
     *              The width of the chart's border in pixels.
     * @param string $fill
     *              The chart's background color. Any valid HTML/CSS color definition
     *              (e. g. "white", "#FFF", "#123456").
     */
    public function __construct($stroke = null, $strokeWidth = null, $fill = null) {
        $this->setOption("stroke", $stroke);
        $this->setOption("strokeWidth", $strokeWidth);
        $this->setOption("fill", $fill);
    }

    /**
     * Sets the chart's border color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setStroke($color) {
        $this->setOption("stroke", $color);
    }

    /**
     * Sets the width of the chart's border.
     *
     * @param int $width
     *              Border-width in pixels. If set to null, the default width will be used.
     */
    public function setStrokeWidth($width) {
        $this->setOption("strokeWidth", $width);
    }

    /**
     * Sets the chart's background color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param type $color
     *              HTML color string. If set to null, the default width will be used.
     */
    public function setFill($color) {
        $this->setOption("fill", $color);
    }

}

?>
